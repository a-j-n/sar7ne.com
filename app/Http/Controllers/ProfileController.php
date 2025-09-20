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
        $user = $request->user()->loadCount(['receivedMessages as total_messages_count']);

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
        ]);

        $normalizedUsername = UsernameGenerator::normalize($validated['username']);

        if ($normalizedUsername === '') {
            return back()
                ->withErrors(['username' => 'Usernames must contain letters or numbers.'])
                ->withInput();
        }

        if ($normalizedUsername !== $user->username && $user->newQuery()->where('username', $normalizedUsername)->exists()) {
            return back()
                ->withErrors(['username' => 'That username is already taken.'])
                ->withInput();
        }

        $updates = [
            'username' => $normalizedUsername,
            'bio' => $validated['bio'] ?? null,
            'display_name' => $validated['display_name'] ?? $user->display_name,
        ];

        $disk = config('filesystems.default', 'public');

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', $disk);

            if ($user->avatar_url && ! Str::startsWith($user->avatar_url, ['http://', 'https://'])) {
                Storage::disk($disk)->delete($user->avatar_url);
            }

            $updates['avatar_url'] = $avatarPath;
        }

        $user->fill($updates)->save();

        return back()->with('status', 'Profile updated successfully.');
    }
}
