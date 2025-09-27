@php
    $theme = request()->cookie('theme', 'dark');

    $descriptions = [
        'light' => __('Always light theme'),
        'system' => __('Match your device preference'),
        'dark' => __('Always dark theme'),
    ];

    $icons = [
        'light' => function () {
            return '<img src="'.asset('light-logo.png').'" alt="Light logo" class="h-6 w-6 rounded-lg bg-white shadow-sm" />';
        },
        'system' => function () {
            return '<div class="flex h-6 w-6 overflow-hidden rounded-lg shadow-sm">'
                .'<img src="'.asset('light-logo.png').'" alt="Light logo preview" class="h-full w-1/2 object-cover" />'
                .'<img src="'.asset('dark-logo.png').'" alt="Dark logo preview" class="h-full w-1/2 object-cover" />'
                .'</div>';
        },
        'dark' => function () {
            return '<img src="'.asset('dark-logo.png').'" alt="Dark logo" class="h-6 w-6 rounded-lg bg-slate-900 shadow-sm" />';
        },
    ];

    $labels = [
        'light' => __('Light'),
        'system' => __('System'),
        'dark' => __('Dark'),
    ];
@endphp

<div x-data="{ open: false }" class="relative">
    <button
        type="button"
        @click="open = !open"
        class="group flex items-center gap-2 rounded-full border border-secondary dark:border-white/10 bg-white/70 dark:bg-slate-900/80 px-3 py-1.5 text-xs font-semibold text-secondary dark:text-slate-100 shadow-sm hover:shadow-md transition-all duration-200"
        aria-haspopup="true"
        :aria-expanded="open.toString()"
    >
        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gradient-orange-pink text-white shadow-sm group-hover:scale-105 transition">
            @if($theme === 'light')
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            @elseif($theme === 'dark')
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            @else
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            @endif
        </span>
        <span class="flex flex-col leading-tight text-left">
            <span class="text-[11px] uppercase tracking-wide text-muted dark:text-slate-400">{{ __('Theme') }}</span>
            <span class="text-sm font-semibold text-primary dark:text-white">{{ $labels[$theme] ?? __('System') }}</span>
        </span>
        <span class="ml-1 text-muted dark:text-slate-400">
            <svg class="h-2.5 w-2.5" viewBox="0 0 12 12" fill="currentColor"><path d="M10.293 3.793a1 1 0 00-1.414 0L6 6.672 3.121 3.793A1 1 0 001.707 5.207l3.586 3.586a1 1 0 001.414 0l3.586-3.586a1 1 0 000-1.414z"/></svg>
        </span>
    </button>

    <div
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        class="absolute right-0 top-full z-50 mt-3 w-64 rounded-2xl border border-secondary/60 dark:border-white/10 bg-primary/95 dark:bg-slate-950/95 shadow-2xl backdrop-blur-xl"
        style="display: none;"
    >
        <div class="p-3 space-y-2">
            <p class="px-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted dark:text-slate-500">{{ __('Choose mode') }}</p>

            @foreach (['light', 'system', 'dark'] as $option)
                @php
                    $isActive = $theme === $option;
                @endphp
                <a
                    href="{{ route('theme.switch', $option) }}"
                    class="group flex items-center gap-3 rounded-xl border border-transparent px-3 py-2.5 transition-all duration-200 {{ $isActive ? 'bg-gradient-orange-pink/10 border-brand-orange/40 shadow-glow glow-brand-orange/30' : 'hover:bg-secondary/80 dark:hover:bg-white/10' }}"
                >
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/70 dark:bg-slate-900/70 shadow-inner">
                        {!! $icons[$option]() !!}
                    </span>
                    <span class="flex flex-col text-left">
                        <span class="text-sm font-semibold text-primary dark:text-white">{{ $labels[$option] }}</span>
                        <span class="text-xs text-muted dark:text-slate-400">{{ $descriptions[$option] }}</span>
                    </span>
                    <span class="ml-auto text-brand-orange opacity-0 transition-opacity duration-200 group-hover:opacity-70 {{ $isActive ? 'opacity-100' : '' }}">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    </span>
                </a>
            @endforeach
        </div>
    </div>

    <noscript>
        <div class="absolute right-0 top-full mt-2 flex gap-1 rounded-lg bg-primary dark:bg-slate-900 p-1 shadow-lg">
            <a href="{{ route('theme.switch', 'light') }}" class="px-2 py-1 text-xs font-semibold {{ $theme === 'light' ? 'text-brand-orange' : 'text-secondary dark:text-slate-300' }}">{{ __('Light') }}</a>
            <a href="{{ route('theme.switch', 'system') }}" class="px-2 py-1 text-xs font-semibold {{ $theme === 'system' ? 'text-brand-orange' : 'text-secondary dark:text-slate-300' }}">{{ __('System') }}</a>
            <a href="{{ route('theme.switch', 'dark') }}" class="px-2 py-1 text-xs font-semibold {{ $theme === 'dark' ? 'text-brand-orange' : 'text-secondary dark:text-slate-300' }}">{{ __('Dark') }}</a>
        </div>
    </noscript>
</div>
