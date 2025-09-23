<div>
    @php $theme = request()->cookie('theme', 'dark'); @endphp

    <button id="theme-toggle" aria-pressed="{{ $theme === 'dark' ? 'true' : 'false' }}" class="rounded px-2 py-1 text-xs bg-transparent text-slate-200 focus:outline-none focus:ring-2 focus:ring-white/20" title="Toggle theme">
        <span class="sr-only">{{ __('messages.language') }}</span>
        @if ($theme === 'dark')
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        @else
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        @endif
    </button>

    <noscript>
        <div class="mt-2">
            <a href="{{ route('theme.switch', 'light') }}" class="inline-block px-2 py-1 text-xs text-slate-300">Light</a>
            <a href="{{ route('theme.switch', 'dark') }}" class="inline-block px-2 py-1 text-xs text-slate-300">Dark</a>
        </div>
    </noscript>

    <script src="{{ asset('js/theme-switcher.js') }}" defer></script>
</div>
