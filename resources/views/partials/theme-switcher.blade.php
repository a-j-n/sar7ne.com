<div>
    @php $theme = request()->cookie('theme', 'system'); @endphp

    <div class="inline-flex items-center gap-2">
        <a href="{{ route('theme.switch', 'light') }}" data-theme="light" class="theme-choice px-2 py-1 text-xs {{ $theme === 'light' ? 'font-semibold' : 'text-slate-300' }}" aria-current="{{ $theme === 'light' ? 'true' : 'false' }}">Light</a>
        <a href="{{ route('theme.switch', 'system') }}" data-theme="system" class="theme-choice px-2 py-1 text-xs {{ $theme === 'system' ? 'font-semibold' : 'text-slate-300' }}" aria-current="{{ $theme === 'system' ? 'true' : 'false' }}">System</a>
        <a href="{{ route('theme.switch', 'dark') }}" data-theme="dark" class="theme-choice px-2 py-1 text-xs {{ $theme === 'dark' ? 'font-semibold' : 'text-slate-300' }}" aria-current="{{ $theme === 'dark' ? 'true' : 'false' }}">Dark</a>
    </div>

    <noscript>
        <div class="mt-2">
            <a href="{{ route('theme.switch', 'light') }}" class="inline-block px-2 py-1 text-xs text-slate-300">Light</a>
            <a href="{{ route('theme.switch', 'system') }}" class="inline-block px-2 py-1 text-xs text-slate-300">System</a>
            <a href="{{ route('theme.switch', 'dark') }}" class="inline-block px-2 py-1 text-xs text-slate-300">Dark</a>
        </div>
    </noscript>

    <script src="{{ asset('js/theme-switcher.js') }}" defer></script>
</div>
