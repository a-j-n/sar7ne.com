<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PublicMessageController extends Controller
{
    public function store(Request $request, User $user): RedirectResponse
    {
        return $this->storeForUser($request, $user);
    }

    public function storeByUsername(Request $request, string $username): RedirectResponse
    {
        $user = User::whereRaw('lower(username) = ?', [strtolower($username)])->firstOrFail();

        return $this->storeForUser($request, $user);
    }

    protected function storeForUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'message_text' => ['required', 'string', 'min:3', 'max:500'],
            'image' => ['nullable', 'image', 'max:2048'], // 2MB max
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $disk = config('filesystems.default', 'public');
            $file = $request->file('image');
            try {
                $converted = \App\Support\ImageConversion::toWebp(
                    $file,
                    (int) config('images.quality', 82),
                    (int) config('images.max_width', 2048),
                    (int) config('images.max_height', 2048)
                );
                $path = 'message-images/'.$converted['filename'];
                \Storage::disk($disk)->put($path, $converted['contents'], ['visibility' => 'public', 'ContentType' => $converted['mime']]);
                $imagePath = $path;
            } catch (\Throwable $e) {
                $fallbackPath = $file->storePublicly('message-images', $disk);
                $imagePath = $fallbackPath;
            }
        }

        Message::create([
            'receiver_id' => $user->id,
            'sender_ip' => $request->ip(),
            'message_text' => $validated['message_text'],
            'image_path' => $imagePath,
            'status' => Message::STATUS_UNREAD,
            'is_public' => false,
        ]);

        return back()->with('status', __('messages.message_delivered_anonymously'));
    }
}
