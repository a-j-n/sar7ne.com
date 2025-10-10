<?php

namespace App\Livewire\Profile;

use App\Support\UsernameGenerator;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Settings extends Component
{
    use WithFileUploads;

    public string $username = '';

    public ?string $display_name = null;

    public ?string $bio = null;

    public ?string $gender = null;

    public bool $allow_public_messages = true;

    public array $social_links = [];

    public array $social_visibility = [];

    #[Rule('nullable|image|max:2048')]
    public $avatar;

    public function mount(): void
    {
        /** @var Authenticatable&\App\Models\User $user */
        $user = Auth::user();
        $this->username = $user->username;
        $this->display_name = $user->display_name;
        $this->bio = $user->bio;
        $this->gender = $user->gender;
        $this->allow_public_messages = (bool) ($user->allow_public_messages ?? true);

        // Initialize social links
        $this->social_links = [
            'twitter' => $user->social_twitter ?? '',
            'instagram' => $user->social_instagram ?? '',
            'tiktok' => $user->social_tiktok ?? '',
            'youtube' => $user->social_youtube ?? '',
            'linkedin' => $user->social_linkedin ?? '',
            'github' => $user->social_github ?? '',
            'website' => $user->social_website ?? '',
        ];

        $this->social_visibility = [
            'twitter' => (bool) ($user->social_twitter_public ?? false),
            'instagram' => (bool) ($user->social_instagram_public ?? false),
            'tiktok' => (bool) ($user->social_tiktok_public ?? false),
            'youtube' => (bool) ($user->social_youtube_public ?? false),
            'linkedin' => (bool) ($user->social_linkedin_public ?? false),
            'github' => (bool) ($user->social_github_public ?? false),
            'website' => (bool) ($user->social_website_public ?? false),
        ];
    }

    public function save(): void
    {
        /** @var Authenticatable&\App\Models\User $user */
        $user = Auth::user();

        $this->validate([
            'username' => ['required', 'string', 'min:3', 'max:20'],
            'display_name' => ['nullable', 'string', 'max:60'],
            'bio' => ['nullable', 'string', 'max:280'],
            'gender' => ['nullable', 'in:male,female,non-binary,other,prefer_not_to_say'],
            'allow_public_messages' => ['sometimes', 'boolean'],
            'social_links.twitter' => ['nullable', 'url', 'max:255'],
            'social_links.instagram' => ['nullable', 'url', 'max:255'],
            'social_links.tiktok' => ['nullable', 'url', 'max:255'],
            'social_links.youtube' => ['nullable', 'url', 'max:255'],
            'social_links.linkedin' => ['nullable', 'url', 'max:255'],
            'social_links.github' => ['nullable', 'url', 'max:255'],
            'social_links.website' => ['nullable', 'url', 'max:255'],
        ]);

        $normalizedUsername = UsernameGenerator::normalize($this->username);
        if ($normalizedUsername === '') {
            $this->addError('username', __('messages.username_must_contain_letters_numbers'));

            return;
        }

        if ($normalizedUsername !== $user->username && $user->newQuery()->where('username', $normalizedUsername)->exists()) {
            $this->addError('username', __('messages.username_already_taken'));

            return;
        }

        $updates = [
            'username' => $normalizedUsername,
            'bio' => $this->bio ?: null,
            'display_name' => $this->display_name ?: $user->display_name,
            'gender' => $this->gender ?: null,
            'allow_public_messages' => (bool) $this->allow_public_messages,
            // Social links
            'social_twitter' => $this->social_links['twitter'] ?: null,
            'social_instagram' => $this->social_links['instagram'] ?: null,
            'social_tiktok' => $this->social_links['tiktok'] ?: null,
            'social_youtube' => $this->social_links['youtube'] ?: null,
            'social_linkedin' => $this->social_links['linkedin'] ?: null,
            'social_github' => $this->social_links['github'] ?: null,
            'social_website' => $this->social_links['website'] ?: null,
            // Social visibility
            'social_twitter_public' => (bool) ($this->social_visibility['twitter'] ?? false),
            'social_instagram_public' => (bool) ($this->social_visibility['instagram'] ?? false),
            'social_tiktok_public' => (bool) ($this->social_visibility['tiktok'] ?? false),
            'social_youtube_public' => (bool) ($this->social_visibility['youtube'] ?? false),
            'social_linkedin_public' => (bool) ($this->social_visibility['linkedin'] ?? false),
            'social_github_public' => (bool) ($this->social_visibility['github'] ?? false),
            'social_website_public' => (bool) ($this->social_visibility['website'] ?? false),
        ];

        $disk = config('filesystems.default', 'spaces');
        if ($this->avatar) {
            $avatarPath = $this->avatar->store('avatars');
            if ($avatarPath && $avatarPath !== '0') {
                if ($user->avatar_url && ! Str::startsWith($user->avatar_url, ['http://', 'https://'])) {
                    Storage::disk($disk)->delete($user->avatar_url);
                }
                $updates['avatar_url'] = Storage::disk($disk)->url($avatarPath);
            } else {
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
        /** @var Authenticatable&\App\Models\User $user */
        $user = Auth::user();

        // Cache message counts briefly to reduce repeated loadCount calls
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

        return view('livewire/pages/profile/settings-page', [
            'user' => $user,
            'total_messages_count' => $counts['total_messages_count'] ?? 0,
            'public_messages_count' => $counts['public_messages_count'] ?? 0,
        ])->title(__('messages.profile').' · sar7ne')
            ->with([
                'meta_description' => $user->bio ?? __('messages.customise_world_sees_you'),
                'og_type' => 'profile',
                'canonical' => route('profile.settings'),
            ]);
    }
}
