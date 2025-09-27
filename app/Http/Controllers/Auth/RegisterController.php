<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\UsernameGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'min:8', 'max:255', 'confirmed'],
        ]);

        $usernameSeed = Str::before($data['email'], '@') ?: $data['email'];

        $username = UsernameGenerator::generate($usernameSeed);

        $user = User::create([
            'username' => $username,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'provider_type' => 'email',
            'provider_id' => $data['email'],
        ]);

        Auth::login($user, true);

        $request->session()->regenerate();

        return redirect()->intended(route('inbox'));
    }
}
