@extends('layouts.app')

@section('title', 'Sign in · sar7ne')

@section('content')
    <div class="mx-auto max-w-lg space-y-6">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-8 text-center shadow-xl">
            <h1 class="text-2xl font-semibold">Join sar7ne</h1>
            <p class="mt-2 text-sm text-slate-300">Sign in with your social account to start receiving anonymous messages.</p>
        </div>

        @if (session('authError'))
            <div class="rounded-xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                {{ session('authError') }}
            </div>
        @endif

        <div class="space-y-4">
            <a href="{{ route('oauth.redirect', ['provider' => 'twitter']) }}" class="flex items-center justify-center gap-3 rounded-2xl border border-white/10 bg-white/10 px-6 py-4 text-sm font-medium text-white transition hover:bg-white/20">
                <svg class="h-5 w-5" viewBox="0 0 1200 1227" fill="currentColor" aria-hidden="true"><path d="M714.163 0c-1.654 0-3.308.408-4.775 1.203-1.466.796-2.678 1.957-3.513 3.373L534.627 323.01 230.79 86.363c-1.36-1.048-3.02-1.622-4.726-1.618-1.707.003-3.363.584-4.717 1.637L14.441 243.498c-1.451 1.077-2.52 2.574-3.03 4.264-.509 1.69-.431 3.506.22 5.151l182.678 463.13L4.627 1189.57c-.483 1.57-.492 3.25-.028 4.826.464 1.577 1.39 2.977 2.659 3.992l206.31 166.62c1.474 1.145 3.306 1.76 5.183 1.733 1.877-.028 3.689-.691 5.12-1.836l307.399-245.13 204.176 245.13c1.092 1.309 2.538 2.289 4.155 2.796 1.617.507 3.349.51 4.968.01l230.92-74.59c1.76-.58 3.237-1.807 4.14-3.438.902-1.63 1.228-3.55.92-5.398l-69.14-413.475 253.02-326.076c1.219-1.663 1.85-3.705 1.794-5.78-.056-2.076-.806-4.062-2.12-5.679L715.43 3.8c-1.492-1.732-3.626-2.791-5.873-2.849-0.13-.003-0.263-.003-0.394-.003Z"/></svg>
                Continue with X (Twitter)
            </a>

            <a href="{{ route('oauth.redirect', ['provider' => 'facebook']) }}" class="flex items-center justify-center gap-3 rounded-2xl border border-white/10 bg-[#0866ff]/80 px-6 py-4 text-sm font-medium text-white transition hover:bg-[#0866ff]">
                <svg class="h-5 w-5" viewBox="0 0 320 512" fill="currentColor" aria-hidden="true"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S274.43 0 243.24 0c-73.22 0-121.05 44.38-121.05 124.72v70.62H56.89V288h65.3v224h100.2V288z"/></svg>
                Continue with Facebook
            </a>
        </div>

        <p class="text-center text-xs text-slate-400">By continuing you agree to our respectful messaging guidelines.</p>
    </div>
@endsection
