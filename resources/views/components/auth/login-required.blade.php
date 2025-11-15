@props([
    'title' => __('messages.sign_in_to_continue') ?? 'Sign in to continue',
    'message' => __('messages.login_required_message') ?? 'You need to sign in to view this page.',
    'loginText' => __('messages.sign_in') ?? 'Sign In',
    'registerText' => __('messages.create_account') ?? 'Create an account',
    'illustration' => null,
])

<x-ui.card class="text-center" padding="p-8 sm:p-10">
    @if($illustration)
        <div class="mx-auto mb-6">
            <img src="{{ $illustration }}" alt="" class="mx-auto h-28 w-auto select-none" draggable="false">
        </div>
    @else
        <div class="mx-auto mb-5 h-14 w-14 rounded-2xl bg-gradient-orange-pink text-white flex items-center justify-center shadow-lg">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M15 3h4a2 2 0 0 1 2 2v4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10 14L21 3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 10v10a2 2 0 0 1-2 2H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    @endif
    <h1 class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $title }}</h1>
    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ $message }}</p>

    <div class="mt-6 flex items-center justify-center gap-3">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-orange-pink px-4 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl focus-visible:outline focus-visible:ring-2 focus-visible:ring-brand-orange focus-visible:ring-offset-2">
            {{ $loginText }}
        </a>
        <a href="{{ route('register') }}" class="text-sm font-medium text-brand-orange hover:underline">
            {{ $registerText }}
        </a>
    </div>
</x-ui.card>
