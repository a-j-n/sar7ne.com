<?php

namespace App\Livewire\Profile;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Info extends Component
{
    use WithFileUploads;

    public ?string $display_name = null;

    public ?string $username = null;

    public ?string $bio = null;

    public ?string $gender = null;

    public $avatar;

    public function mount(): void
    {
        /** @var (Authenticatable&\App\Models\User)|null $user */
        $user = Auth::user();
        if (! $user) {
            return; // Guest users will see the login-required component in the view
        }
        $this->display_name = $user->display_name;
        $this->username = $user->username;
        $this->bio = $user->bio;
        $this->gender = $user->gender;
    }

    public function saveBasic(): void
    {
        /** @var (Authenticatable&\App\Models\User)|null $user */
        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        $this->validate([
            'display_name' => ['nullable', 'string', 'max:60'],
            'username' => ['required', 'string', 'min:3', 'max:20'],
            'bio' => ['nullable', 'string', 'max:280'],
            'gender' => ['nullable', 'in:male,female,non-binary,other,prefer_not_to_say'],
            'avatar' => ['nullable', 'image', 'max:5120'],
        ]);

        // Normalize and ensure username uniqueness if changed
        $normalized = \App\Support\UsernameGenerator::normalize($this->username ?? '');
        if ($normalized === '') {
            $this->addError('username', __('messages.username_must_contain_letters_numbers'));

            return;
        }
        if ($normalized !== $user->username && $user->newQuery()->where('username', $normalized)->exists()) {
            $this->addError('username', __('messages.username_already_taken'));

            return;
        }

        $updates = [
            'display_name' => $this->display_name ?: $user->display_name,
            'username' => $normalized,
            'bio' => $this->bio ?: null,
            'gender' => $this->gender ?: null,
        ];

        $disk = config('filesystems.default', 'spaces');
        if ($this->avatar) {
            try {
                $converted = \App\Support\ImageConversion::toWebp(
                    $this->avatar,
                    (int) config('images.quality', 82),
                    (int) config('images.avatar.max_width', 512),
                    (int) config('images.avatar.max_height', 512)
                );
                $avatarPath = 'avatars/'.$converted['filename'];
                Storage::disk($disk)->put($avatarPath, $converted['contents'], ['visibility' => 'public', 'ContentType' => $converted['mime']]);
                if ($user->avatar_url && ! Str::startsWith($user->avatar_url, ['http://', 'https://'])) {
                    Storage::disk($disk)->delete($user->avatar_url);
                }
                $updates['avatar_url'] = Storage::disk($disk)->url($avatarPath);
            } catch (\Throwable $e) {
                $this->addError('avatar', __('messages.avatar_upload_failed'));

                return;
            }
        }

        $user->fill($updates)->save();

        Cache::forget("user:counts:{$user->id}");
        session()->flash('status', __('messages.settings_saved'));
    }

    public function render()
    {
        /** @var (Authenticatable&\App\Models\User)|null $user */
        $user = Auth::user();
        if (! $user) {
            // Render minimal view context for guests (view handles guest state)
            return view('livewire/pages/profile/info', [
                'user' => null,
                'total_messages_count' => 0,
                'public_messages_count' => 0,
            ])->title(__('messages.profile').' · sar7ne')
                ->with([
                    'meta_description' => __('messages.customise_world_sees_you'),
                    'og_type' => 'profile',
                    'canonical' => route('profile.info'),
                ]);
        }

        $counts = Cache::remember("user:counts:{$user->id}", 60, function () use ($user) {
            $userCounts = $user->loadCount([
                'receivedMessages as total_messages_count',
                'publicMessages as public_messages_count',
            ]);

            return [
                'total_messages_count' => $userCounts->total_messages_count,
                'public_messages_count' => $userCounts->public_messages_count,
            ];
        });

        return view('livewire/pages/profile/info', [
            'user' => $user,
            'total_messages_count' => $counts['total_messages_count'] ?? 0,
            'public_messages_count' => $counts['public_messages_count'] ?? 0,
        ])->title(__('messages.profile').' · sar7ne')
            ->with([
                'meta_description' => $user->bio ?? __('messages.customise_world_sees_you'),
                'og_type' => 'profile',
                'canonical' => route('profile.info'),
            ]);
    }
}
