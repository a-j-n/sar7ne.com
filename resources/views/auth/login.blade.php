@extends('layouts.app')

@section('title', __('messages.sign_in_title'))

@section('content')
    <div class="mx-auto max-w-lg space-y-6">
        <div class="rounded-3xl border border-slate-200 dark:border-slate-700/60 bg-white text-black dark:bg-slate-900/70 p-8 text-center shadow-xl">
            <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ __('messages.join_sar7ne') }}</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ __('messages.auth_description') }}</p>
        </div>

        @if (session('authError'))
            <div class="rounded-xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-700 dark:text-red-200">
                {{ session('authError') }}
            </div>
        @endif

        <div class="space-y-4">


            <a href="{{ route('oauth.redirect', ['provider' => 'twitter']) }}" class="flex items-center justify-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-700/60 bg-white text-black dark:bg-slate-900/70 px-6 py-4 text-sm font-medium transition hover:bg-slate-50 dark:hover:bg-slate-800/70">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M23.954 4.569c-.885.392-1.83.656-2.825.775 1.014-.608 1.794-1.574 2.163-2.723-.949.564-2.005.974-3.127 1.195-.897-.959-2.178-1.555-3.594-1.555-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124-4.09-.205-7.719-2.165-10.148-5.144-.424.729-.666 1.574-.666 2.476 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.062c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.317 0-.626-.03-.927-.086.627 1.956 2.444 3.379 4.6 3.419-1.68 1.319-3.809 2.105-6.102 2.105-.396 0-.788-.023-1.175-.067 2.179 1.397 4.768 2.212 7.548 2.212 9.054 0 14-7.496 14-13.986 0-.21-.005-.423-.014-.634.961-.695 1.797-1.562 2.457-2.549z"/>
                </svg>
                {{ __('messages.continue_with', ['provider' => 'Twitter']) }}
            </a>

            <div class="text-center text-xs uppercase tracking-wide text-slate-600 dark:text-slate-400">{{ __('messages.or_continue_with') }}</div>

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-3 rounded-2xl border border-slate-200 dark:border-slate-700/60 bg-white text-black dark:bg-slate-900/70 p-6">
                @csrf
                <div class="text-left">
                    <label for="email" class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-300">{{ __('messages.email_label') }}</label>
                    <x-ui.input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('messages.email_placeholder', ['example' => 'you@example.com']) }}" />
                    @error('email')
                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                    @enderror
                </div>
                <div class="text-left">
                    <label for="password" class="mb-1 block text-xs font-medium text-slate-700 dark:text-slate-300">{{ __('messages.password_label') }}</label>
                    <x-ui.input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••" />
                    @error('password')
                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300">
                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900/70">
                        {{ __('messages.remember_me') }}
                    </label>
                    <button type="submit" class="rounded-xl bg-slate-900 dark:bg-brand-orange px-4 py-2 text-xs font-medium text-white dark:text-white transition hover:bg-black dark:hover:bg-brand-orange/90">{{ __('messages.sign_in_with_email') }}</button>
                </div>
            </form>


{{--            hidden for now--}}
{{--            <a href="{{ route('oauth.redirect', ['provider' => 'facebook']) }}" class="flex items-center justify-center gap-3 rounded-2xl border border-white/10 bg-[#0866ff]/80 px-6 py-4 text-sm font-medium text-white transition hover:bg-[#0866ff]">--}}
{{--                <svg class="h-5 w-5" viewBox="0 0 320 512" fill="currentColor" aria-hidden="true"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S274.43 0 243.24 0c-73.22 0-121.05 44.38-121.05 124.72v70.62H56.89V288h65.3v224h100.2V288z"/></svg>--}}
{{--                Continue with Facebook--}}
{{--            </a>--}}
        </div>

        <p class="text-center text-xs text-slate-600 dark:text-slate-400">{{ __('messages.respectful_guidelines') }}</p>
        <p class="text-center text-xs text-slate-600 dark:text-slate-400">{{ __('messages.new_here') }} <a class="text-slate-900 dark:text-white underline" href="{{ route('register') }}">{{ __('messages.create_account') }}</a> · <a class="text-slate-900 dark:text-white underline" href="{{ route('password.request') }}">{{ __('messages.forgot_password') }}</a></p>
    </div>
@endsection
