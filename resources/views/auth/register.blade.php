@extends('layouts.app')

@section('title', __('messages.create_account_heading'))

@section('content')
    <div class="mx-auto max-w-3xl space-y-8">
        <x-ui.card padding="p-6 sm:p-8" class="relative overflow-hidden card-brand-gradient">
            <div class="absolute inset-0 bg-gradient-brand-glow opacity-10"></div>
            <div class="absolute -right-14 -top-10 h-32 w-32 rounded-full bg-brand-orange/25 blur-3xl"></div>
            <div class="absolute -left-10 bottom-0 h-32 w-32 rounded-full bg-neon-mint/25 blur-3xl"></div>
            <div class="relative flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-2">
                    <h1 class="text-3xl sm:text-4xl font-black leading-tight text-slate-900 dark:text-white">{{ __('messages.create_account_heading') }}</h1>
                    <p class="text-sm sm:text-base text-slate-700 dark:text-slate-200 leading-relaxed max-w-2xl break-words">
                        {{ __('messages.register_description') }}
                    </p>
                </div>
                <div class="inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/70 px-3 py-1.5 text-xs font-semibold text-slate-800 shadow-sm dark:border-slate-700 dark:bg-slate-900/70 dark:text-white">
                    <span class="h-2 w-2 rounded-full bg-brand-orange animate-pulse"></span>
                    {{ __('messages.anonymous_by_default') }}
                </div>
            </div>
        </x-ui.card>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <x-ui.card padding="p-6 sm:p-7" class="space-y-4">
            <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
            @csrf
                <div class="text-left">
                    <label for="email" class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-300">{{ __('messages.email_label') }}</label>
                    <x-ui.input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('messages.email_placeholder') }}" />
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="text-left">
                        <label for="password" class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-300">{{ __('messages.password_label') }}</label>
                        <x-ui.input id="password" name="password" type="password" required autocomplete="new-password" placeholder="••••••••" />
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="text-left">
                        <label for="password_confirmation" class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-300">{{ __('messages.password_label') }}</label>
                        <x-ui.input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="••••••••" />
                    </div>
                </div>
                <x-ui.button type="submit" variant="primary" class="w-full inline-flex items-center justify-center gap-2">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ __('messages.create_account_button') }}
                </x-ui.button>
            </form>
            <div class="text-center text-xs text-slate-600 dark:text-slate-400">
                {!! __('messages.already_have_account', ['link' => '<a href="'.route('login').'" class="text-brand-orange font-semibold hover:underline">'.__('messages.login').'</a>']) !!}
            </div>
        </x-ui.card>

    </div>
@endsection
