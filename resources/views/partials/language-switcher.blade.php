<div class="relative inline-block text-left">
    @php
        $current = app()->getLocale();
        $available = config('locales.available', []);
        $currentName = $available[$current]['name'] ?? strtoupper($current);
    @endphp

    <!-- Trigger Button -->
    <button id="lang-toggle" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="lang-dialog" class="inline-flex items-center gap-2 rounded px-2 py-1 text-xs bg-transparent text-slate-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-white/20">
        <span class="sr-only">{{ __('messages.language') }}</span>
        <span>{{ $currentName }}</span>
        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path d="M6 8l4 4 4-4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
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
