@extends('layouts.app')

@section('title', 'Register · sar7ne')

@section('content')
    <div class="mx-auto max-w-lg space-y-6">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-8 text-center shadow-xl">
            <h1 class="text-2xl font-semibold">Create your account</h1>
            <p class="mt-2 text-sm text-slate-300">Sign up with your email. We'll generate a username for you.</p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="space-y-3 rounded-2xl border border-white/10 bg-white/5 p-6">
            @csrf
            <div class="text-left">
                <label for="email" class="mb-1 block text-xs font-medium text-slate-300">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="w-full rounded-xl border border-white/10 bg-black/20 px-3 py-2 text-sm text-white placeholder-slate-400 outline-none focus:border-white/30" placeholder="you@example.com">
                @error('email')
                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>
            <div class="text-left">
                <label for="password" class="mb-1 block text-xs font-medium text-slate-300">Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-white/10 bg-black/20 px-3 py-2 text-sm text-white placeholder-slate-400 outline-none focus:border-white/30" placeholder="••••••••">
                @error('password')
                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>
            <div class="text-left">
                <label for="password_confirmation" class="mb-1 block text-xs font-medium text-slate-300">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-white/10 bg-black/20 px-3 py-2 text-sm text-white placeholder-slate-400 outline-none focus:border-white/30" placeholder="••••••••">
            </div>
            <button type="submit" class="w-full rounded-xl bg-white/90 px-4 py-2 text-sm font-medium text-black transition hover:bg-white">Create Account</button>
        </form>

        <p class="text-center text-xs text-slate-400">Already have an account? <a href="{{ route('login') }}" class="text-white underline">Sign in</a></p>
    </div>
@endsection

