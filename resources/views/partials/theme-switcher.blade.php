<div>
    @php $theme = request()->cookie('theme', 'system'); @endphp

    <!-- Trigger Button (shadcn style) -->
    <button
        id="theme-dialog-trigger"
        type="button"
        aria-haspopup="dialog"
        aria-controls="theme-dialog"
        class="inline-flex items-center justify-center p-2 rounded-md bg-transparent text-slate-200 hover:bg-white/5 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-white/20"
        aria-label="Change theme"
    >
        <span class="sr-only">Change theme</span>
        <!-- Sun / Moon combined icon -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5">
            <mask id="moon-mask">
                <rect x="0" y="0" width="100%" height="100%" fill="white" />
                <circle cx="14" cy="10" r="6" fill="black" />
            </mask>
            <g mask="url(#moon-mask)">
                <circle cx="12" cy="12" r="5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </g>
            <path d="M12 1v2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M12 21v2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M4.22 4.22l1.42 1.42" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M18.36 18.36l1.42 1.42" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M1 12h2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M21 12h2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M4.22 19.78l1.42-1.42" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M18.36 5.64l1.42-1.42" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </button>

    <!-- Dialog (hidden by default) -->
    <div id="theme-dialog" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-black/50" data-dialog-overlay></div>

        <div class="fixed inset-0 flex items-end sm:items-center justify-center p-4">
            <div role="dialog" aria-modal="true" aria-labelledby="theme-dialog-title" class="w-full max-w-sm rounded-lg bg-white dark:bg-slate-900 shadow-lg ring-1 ring-black/5 p-4">
                <div class="flex items-center justify-between">
                    <h3 id="theme-dialog-title" class="text-sm font-semibold">Select Theme</h3>
                    <button type="button" data-dialog-close aria-label="Close" class="text-slate-500 hover:text-slate-900 dark:hover:text-white">✕</button>
                </div>

                <div class="mt-4 flex flex-col gap-2">
                    <button type="button" data-theme="light" data-href="{{ route('theme.switch', 'light') }}" class="theme-choice text-left px-3 py-2 rounded-md {{ $theme === 'light' ? 'font-semibold bg-slate-100 dark:bg-slate-800' : 'text-slate-500' }}">Light</button>
                    <button type="button" data-theme="system" data-href="{{ route('theme.switch', 'system') }}" class="theme-choice text-left px-3 py-2 rounded-md {{ $theme === 'system' ? 'font-semibold bg-slate-100 dark:bg-slate-800' : 'text-slate-500' }}">System</button>
                    <button type="button" data-theme="dark" data-href="{{ route('theme.switch', 'dark') }}" class="theme-choice text-left px-3 py-2 rounded-md {{ $theme === 'dark' ? 'font-semibold bg-slate-100 dark:bg-slate-800' : 'text-slate-500' }}">Dark</button>
                </div>

                <noscript>
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('theme.switch', 'light') }}" class="inline-block px-3 py-2 text-xs text-slate-300">Light</a>
                        <a href="{{ route('theme.switch', 'system') }}" class="inline-block px-3 py-2 text-xs text-slate-300">System</a>
                        <a href="{{ route('theme.switch', 'dark') }}" class="inline-block px-3 py-2 text-xs text-slate-300">Dark</a>
                    </div>
                </noscript>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/theme-switcher.js') }}" defer></script>
</div>
