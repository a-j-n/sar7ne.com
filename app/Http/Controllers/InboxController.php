<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index(Request $request): View
    {
        $messages = $request->user()
            ->receivedMessages()
            ->paginate(12);

        $unreadCount = $request->user()
            ->receivedMessages()
            ->where('status', Message::STATUS_UNREAD)
            ->count();

        return view('inbox', [
            'messages' => $messages,
            'unreadCount' => $unreadCount,
        ]);
    }
}
