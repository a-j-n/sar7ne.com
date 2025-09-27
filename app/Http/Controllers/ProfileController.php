<?php

namespace App\Http\Controllers;

use App\Support\UsernameGenerator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user()->loadCount([
            'receivedMessages as total_messages_count',
            'publicMessages as public_messages_count',
        ]);

        return view('profile', [
            'user' => $user,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:20'],
            'display_name' => ['nullable', 'string', 'max:60'],
            'bio' => ['nullable', 'string', 'max:280'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'gender' => ['nullable', 'in:male,female,non-binary,other,prefer_not_to_say'],
            'allow_public_messages' => ['sometimes', 'boolean'],
        ]);

        $normalizedUsername = UsernameGenerator::normalize($validated['username']);

        if ($normalizedUsername === '') {
            return back()
                ->withErrors(['username' => __('messages.username_must_contain_letters_numbers')])
                ->withInput();
        }

        if ($normalizedUsername !== $user->username && $user->newQuery()->where('username', $normalizedUsername)->exists()) {
            return back()
                ->withErrors(['username' => __('messages.username_already_taken')])
                ->withInput();
        }

        $updates = [
            'username' => $normalizedUsername,
            'bio' => $validated['bio'] ?? null,
            'display_name' => $validated['display_name'] ?? $user->display_name,
            'gender' => $validated['gender'] ?? null,
            'allow_public_messages' => (bool) ($validated['allow_public_messages'] ?? false),
        ];

        $disk = config('filesystems.default', 'spaces');
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars');
            if ($avatarPath && $avatarPath !== '0') {
                if ($user->avatar_url && ! Str::startsWith($user->avatar_url, ['http://', 'https://'])) {
                    Storage::disk($disk)->delete($user->avatar_url);
                }
                // Store the full URL for Spaces
                $updates['avatar_url'] = Storage::disk($disk)->url($avatarPath);
            } else {
                return back()->withErrors(['avatar' => __('messages.avatar_upload_failed')])->withInput();
            }
        }

        $user->fill($updates)->save();

        return back()->with('status', __('messages.profile_updated_successfully'));
    }
}
