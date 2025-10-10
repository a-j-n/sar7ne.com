@extends('layouts.app')

@section('title', __('messages.create_account_heading'))

@section('content')
    <div class="mx-auto max-w-lg space-y-6">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-8 text-center shadow-xl">
            <h1 class="text-2xl font-semibold">{{ __('messages.create_account_heading') }}</h1>
            <p class="mt-2 text-sm text-slate-300">{{ __('messages.register_description') }}</p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="space-y-3 rounded-2xl border border-white/10 bg-white/5 p-6">
            @csrf
            <div class="text-left">
                <label for="email" class="mb-1 block text-xs font-medium text-slate-300">{{ __('messages.email_label') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-black placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20" placeholder="{{ __('messages.email_placeholder') }}">
                @error('email')
                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>
            <div class="text-left">
                <label for="password" class="mb-1 block text-xs font-medium text-slate-300">{{ __('messages.password_label') }}</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-black placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20" placeholder="••••••••">
                @error('password')
                    <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>
            <div class="text-left">
                <label for="password_confirmation" class="mb-1 block text-xs font-medium text-slate-300">{{ __('messages.password_label') }}</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-black placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20" placeholder="••••••••">
            </div>
            <button type="submit" class="w-full rounded-xl bg-white/90 px-4 py-2 text-sm font-medium text-black transition hover:bg-white">{{ __('messages.create_account_button') }}</button>
        </form>

        <p class="text-center text-xs text-slate-400">{!! __('messages.already_have_account', ['link' => '<a href="'.route('login').'" class="text-white underline">'.__('messages.login').'</a>']) !!}</p>
    </div>
@endsection
