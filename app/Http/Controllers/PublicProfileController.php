<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;

class PublicProfileController extends Controller
{
    public function show(User $user): View
    {
        $user->loadCount(['receivedMessages as total_messages_count']);

        return view('public.profile', [
            'user' => $user,
        ]);
    }

    public function showByUsername(string $username): View
    {
        $user = User::whereRaw('lower(username) = ?', [strtolower($username)])->firstOrFail();

        return $this->show($user);
    }
}
