<div class="relative inline-block text-left">
    @php
        $current = app()->getLocale();
        $available = config('locales.available', []);
        $currentName = $available[$current]['name'] ?? strtoupper($current);
    @endphp

    <!-- Trigger Button -->
    <button id="lang-toggle" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="lang-dialog" class="inline-flex items-center justify-center rounded p-2 bg-transparent text-slate-200 hover:bg-white/5 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-white/20" aria-label="Change language">
        <span class="sr-only">{{ __('messages.language') }}</span>
        <!-- Globe icon (removed xmlns attribute to avoid linter warning) -->
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5">
            <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M2.05 12H21.95" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M12 2.05a15.9 15.9 0 010 19.9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M7 4.5c1.5 1 3.5 1.5 5 1.5s3.5-.5 5-1.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </button>

    <!-- Dialog (initially hidden) -->
    <div id="lang-dialog" role="dialog" aria-modal="true" aria-labelledby="lang-dialog-title" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">
        <!-- Overlay -->
        <div id="lang-dialog-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm" aria-hidden="true"></div>

        <!-- Dialog panel -->
        <div class="relative z-10 w-full max-w-sm rounded-xl bg-[#0b0b0f] p-4 shadow-lg ring-1 ring-white/5">
            <div class="flex items-center justify-between">
                <h2 id="lang-dialog-title" class="text-sm font-semibold">{{ __('messages.language') }}</h2>
                <button id="lang-dialog-close" aria-label="Close" class="rounded px-2 py-1 text-sm text-slate-300 hover:bg-white/5">✕</button>
            </div>

            <div class="mt-3 grid gap-2">
                @foreach ($available as $code => $meta)
                    <a href="{{ route('language.switch', $code) }}" data-lang="{{ $code }}" data-dir="{{ $meta['dir'] ?? 'ltr' }}" class="lang-choice block rounded px-3 py-2 text-sm text-slate-200 hover:bg-white/5">{{ $meta['name'] ?? $code }}</a>
                @endforeach
            </div>
        </div>
    </div>

    <noscript>
        <div class="mt-2">
            @foreach ($available as $code => $meta)
                <a href="{{ route('language.switch', $code) }}" class="inline-block px-2 py-1 text-xs text-slate-300">{{ $meta['name'] ?? $code }}</a>
            @endforeach
        </div>
    </noscript>

    <script src="{{ asset('js/lang-switcher.js') }}" defer></script>
</div>
