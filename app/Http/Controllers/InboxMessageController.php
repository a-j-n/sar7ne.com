<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InboxMessageController extends Controller
{
    public function markRead(Request $request, Message $message): RedirectResponse
    {
        $this->authorizeMessage($request, $message);

        $message->markRead();

        return back()->with('status', 'Message marked as read.');
    }

    public function markUnread(Request $request, Message $message): RedirectResponse
    {
        $this->authorizeMessage($request, $message);

        $message->markUnread();

        return back()->with('status', 'Message marked as unread.');
    }

    public function destroy(Request $request, Message $message): RedirectResponse
    {
        $this->authorizeMessage($request, $message);

        $message->delete();

        return back()->with('status', 'Message deleted.');
    }

    public function togglePublic(Request $request, Message $message): RedirectResponse
    {
        $this->authorizeMessage($request, $message);

        $user = $request->user();
        if (! $user->allow_public_messages) {
            return back()->withErrors(['public' => __('messages.public_messages_disabled')]);
        }

        $validated = $request->validate([
            'is_public' => ['required', 'boolean'],
        ]);

        $message->forceFill([
            'is_public' => (bool) $validated['is_public'],
        ])->save();

        return back()->with('status', __('messages.message_privacy_updated'));
    }

    protected function authorizeMessage(Request $request, Message $message): void
    {
        abort_if($message->receiver_id !== $request->user()->id, 403);
    }
}
