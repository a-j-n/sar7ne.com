<div class="relative inline-block text-left">
    @php
        $current = app()->getLocale();
        $available = config('locales.available', []);
        $currentName = $available[$current]['name'] ?? strtoupper($current);
    @endphp

    <button id="lang-toggle" type="button" aria-haspopup="true" aria-expanded="false" class="inline-flex items-center gap-2 rounded px-2 py-1 text-xs bg-transparent text-slate-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-white/20">
        <span class="sr-only">{{ __('messages.language') }}</span>
        <span aria-hidden="true">{{ $currentName }}</span>
        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path d="M6 8l4 4 4-4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>

    <div id="lang-menu" class="z-50 mt-2 w-40 origin-top-right rounded-md bg-[#05070d] shadow-lg ring-1 ring-black/20" role="menu" aria-hidden="true" style="display:none;">
        <div class="py-1">
            @foreach ($available as $code => $meta)
                <a href="{{ route('language.switch', $code) }}" role="menuitem" tabindex="-1" data-dir="{{ $meta['dir'] ?? 'ltr' }}" class="block px-3 py-2 text-sm text-slate-200 hover:bg-white/5">{{ $meta['name'] ?? $code }}</a>
            @endforeach
        </div>
    </div>

    <noscript>
        <div class="mt-2">
            @foreach ($available as $code => $meta)
                <a href="{{ route('language.switch', $code) }}" class="inline-block px-2 py-1 text-xs text-slate-300">{{ $meta['name'] ?? $code }}</a>
            @endforeach
        </div>
    </noscript>

    <script src="/js/lang-switcher.js" defer></script>
</div>
