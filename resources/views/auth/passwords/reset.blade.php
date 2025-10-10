@extends('layouts.app')

@section('title', 'Reset Password · sar7ne')

@section('content')
    <div class="mx-auto max-w-lg space-y-6">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-8 text-center shadow-xl">
            <h1 class="text-2xl font-semibold">Set a new password</h1>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-3 rounded-2xl border border-white/10 bg-white/5 p-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ old('email', $email) }}">

            <div class="text-left">
                <label for="password" class="mb-1 block text-xs font-medium text-slate-300">New Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-black placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20" placeholder="••••••••">
                @error('password')
                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>
            <div class="text-left">
                <label for="password_confirmation" class="mb-1 block text-xs font-medium text-slate-300">Confirm New Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-black placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20" placeholder="••••••••">
            </div>
            <button type="submit" class="w-full rounded-xl bg-white/90 px-4 py-2 text-sm font-medium text-black transition hover:bg-white">Reset Password</button>
        </form>

        <p class="text-center text-xs text-slate-400"><a href="{{ route('login') }}" class="text-white underline">Back to sign in</a></p>
    </div>
@endsection
