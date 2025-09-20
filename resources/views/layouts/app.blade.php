<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'sar7ne')</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#0d6efd">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    </head>
    <body class="min-h-screen bg-[#05070d] font-sans text-slate-100 antialiased">
        <div class="min-h-screen pb-20">
            <header class="border-b border-white/5 bg-gradient-to-r from-white/5 via-white/0 to-white/5 py-4">
                <div class="mx-auto flex w-full max-w-4xl items-center justify-between px-4">
                    <a href="{{ route('explore') }}" class="flex items-center gap-2 text-lg font-semibold tracking-tight">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-sm font-bold uppercase">S7</span>
                        <span>sar7ne</span>
                    </a>
                    <div class="flex items-center gap-3 text-sm text-slate-300">
                        @auth
                            <span class="hidden text-sm font-medium sm:inline">{{ auth()->user()->username }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white transition hover:bg-white/20">
                                    Log out
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white transition hover:bg-white/20">Sign in</a>
                        @endauth
                    </div>
                </div>
            </header>

            @if (session('status'))
                <div class="mx-auto mt-4 w-full max-w-2xl px-4">
                    <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mx-auto mt-4 w-full max-w-2xl px-4">
                    <div class="rounded-xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                        <span class="font-semibold">We found some issues:</span>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <main class="mx-auto w-full max-w-4xl px-4 py-8">
                @yield('content')
            </main>
        </div>

        <nav class="fixed inset-x-0 bottom-0 border-t border-white/10 bg-[#05070d]/90 backdrop-blur">
            <div class="mx-auto grid w-full max-w-4xl grid-cols-3">
                @php
                    $navItems = [
                        ['label' => 'Explore', 'href' => route('explore'), 'active' => request()->routeIs('explore'), 'icon' => 'explore'],
                        ['label' => 'Inbox', 'href' => auth()->check() ? route('inbox') : route('login'), 'active' => request()->routeIs('inbox*'), 'icon' => 'inbox'],
                        ['label' => 'Profile', 'href' => auth()->check() ? route('profile') : route('login'), 'active' => request()->routeIs('profile'), 'icon' => 'profile'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    <a href="{{ $item['href'] }}" class="flex flex-col items-center justify-center gap-1 py-3 text-xs font-medium {{ $item['active'] ? 'text-white' : 'text-slate-400 hover:text-white' }}">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full {{ $item['active'] ? 'bg-white/15 text-white' : 'bg-white/5 text-slate-300' }}">
                            @switch($item['icon'])
                                @case('explore')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2v2"/><path d="M12 20v2"/><path d="M4.93 4.93l1.41 1.41"/><path d="M17.66 17.66l1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="M6.34 17.66l-1.41 1.41"/><path d="M19.07 4.93l-1.41 1.41"/><circle cx="12" cy="12" r="4"/></svg>
                                    @break
                                @case('inbox')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/><path d="M3 13h3l2 3h8l2-3h3"/><path d="m7 8 5 4 5-4"/></svg>
                                    @break
                                @case('profile')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M6 20a6 6 0 0 1 12 0"/></svg>
                                    @break
                            @endswitch
                        </span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </nav>
    </body>
</html>
