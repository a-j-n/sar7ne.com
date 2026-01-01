@extends('layouts.app')

@section('title', __('messages.sign_in_title'))

@section('content')
    <div class="mx-auto max-w-3xl space-y-8">
        <x-ui.card padding="p-6 sm:p-8" class="relative overflow-hidden card-brand-gradient">
            <div class="absolute inset-0 bg-gradient-brand-glow opacity-10"></div>
            <div class="absolute -right-14 -top-10 h-32 w-32 rounded-full bg-brand-orange/25 blur-3xl"></div>
            <div class="absolute -left-10 bottom-0 h-32 w-32 rounded-full bg-neon-mint/25 blur-3xl"></div>
            <div class="relative grid gap-4 sm:grid-cols-[1.1fr,0.9fr] items-start">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/40 bg-white/50 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.08em] text-brand-orange shadow-sm dark:border-slate-700/70 dark:bg-slate-900/60">
                        <span class="h-2 w-2 rounded-full bg-brand-orange animate-pulse"></span>
                        {{ __('messages.sign_in') }}
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-black leading-tight text-slate-900 dark:text-white">{{ __('messages.join_sar7ne') }}</h1>
                    <p class="text-sm sm:text-base text-slate-700 dark:text-slate-300 leading-relaxed max-w-xl">
                        {{ __('messages.auth_description') }}
                    </p>
                </div>
                <div class="grid gap-3 rounded-2xl border border-white/60 bg-white/70 p-4 shadow-lg backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
                    <div class="flex items-center gap-3">
                        <span class="h-10 w-10 rounded-xl bg-gradient-orange-pink text-white flex items-center justify-center shadow-inner">🔒</span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('messages.anonymous_by_default') }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400">{{ __('messages.protected_from_spam') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="h-10 w-10 rounded-xl bg-white text-slate-800 flex items-center justify-center shadow-inner ring-1 ring-slate-200 dark:bg-slate-800 dark:text-white dark:ring-slate-700">✨</span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('messages.shareable_profile_link') }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400">{{ __('messages.browse_profiles_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </x-ui.card>

        @if (session('authError'))
            <div class="rounded-xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-700 dark:text-red-200">
                {{ session('authError') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <a href="{{ route('oauth.redirect', ['provider' => 'twitter']) }}" class="flex items-center justify-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-700/60 bg-white text-black dark:bg-slate-900/70 px-6 py-4 text-sm font-medium transition hover:bg-slate-50 dark:hover:bg-slate-800/70">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M23.954 4.569c-.885.392-1.83.656-2.825.775 1.014-.608 1.794-1.574 2.163-2.723-.949.564-2.005.974-3.127 1.195-.897-.959-2.178-1.555-3.594-1.555-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124-4.09-.205-7.719-2.165-10.148-5.144-.424.729-.666 1.574-.666 2.476 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.062c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.317 0-.626-.03-.927-.086.627 1.956 2.444 3.379 4.6 3.419-1.68 1.319-3.809 2.105-6.102 2.105-.396 0-.788-.023-1.175-.067 2.179 1.397 4.768 2.212 7.548 2.212 9.054 0 14-7.496 14-13.986 0-.21-.005-.423-.014-.634.961-.695 1.797-1.562 2.457-2.549z"/>
                </svg>
                {{ __('messages.continue_with', ['provider' => 'Twitter']) }}
            </a>
            <a href="{{ route('register') }}" class="flex items-center justify-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-700/60 bg-white text-black dark:bg-slate-900/70 px-6 py-4 text-sm font-semibold transition hover:bg-slate-50 dark:hover:bg-slate-800/70">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                {{ __('messages.create_account') }}
            </a>
        </div>

        <div class="text-center text-xs uppercase tracking-wide text-slate-600 dark:text-slate-400">{{ __('messages.or_continue_with') }}</div>

        <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4 rounded-2xl border border-slate-200 dark:border-slate-700/60 bg-white text-black dark:bg-slate-900/70 p-6">
            @csrf
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="text-left sm:col-span-2">
                    <label for="email" class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-300">{{ __('messages.email_label') }}</label>
                    <x-ui.input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('messages.email_placeholder', ['example' => 'you@example.com']) }}" />
                    @error('email')
                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                    @enderror
                </div>
                <div class="text-left sm:col-span-2">
                    <label for="password" class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-300">{{ __('messages.password_label') }}</label>
                    <x-ui.input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••" />
                    @error('password')
                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-2">
                <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300">
                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900/70">
                    {{ __('messages.remember_me') }}
                </label>
                <div class="flex items-center gap-3">
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-brand-orange hover:underline">{{ __('messages.forgot_password') }}</a>
                    <x-ui.button type="submit" variant="primary" size="sm" class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M15 3h4a2 2 0 012 2v4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 14L21 3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 10v10a2 2 0 01-2 2H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        {{ __('messages.sign_in_with_email') }}
                    </x-ui.button>
                </div>
            </div>
        </form>

        <div class="rounded-2xl border border-slate-200 dark:border-slate-700/60 bg-white/70 dark:bg-slate-900/70 p-4 text-xs text-slate-600 dark:text-slate-400 text-center">
            {{ __('messages.respectful_guidelines') }}
        </div>
        <p class="text-center text-xs text-slate-600 dark:text-slate-400">
            {{ __('messages.new_here') }}
            <a class="text-brand-orange font-semibold hover:underline" href="{{ route('register') }}">{{ __('messages.create_account') }}</a>
            ·
            <a class="text-brand-orange font-semibold hover:underline" href="{{ route('password.request') }}">{{ __('messages.forgot_password') }}</a>
        </p>
    </div>
@endsection
