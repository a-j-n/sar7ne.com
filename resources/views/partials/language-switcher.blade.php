<div>
    @php
        $current = app()->getLocale();
        $available = config('locales.available', []);
    @endphp

    <label for="locale-select" class="sr-only">{{ __('messages.language') }}</label>
    <select id="locale-select" class="rounded px-2 py-1 text-xs bg-transparent text-slate-200" aria-label="{{ __('messages.language') }}" onchange="(function(el){ const opt = el.options[el.selectedIndex]; const dir = opt.dataset.dir || 'ltr'; document.documentElement.setAttribute('dir', dir); if (opt.value) { window.location.href = opt.value; } })(this)">
        @foreach ($available as $code => $meta)
            <option value="{{ route('language.switch', $code) }}" data-dir="{{ $meta['dir'] ?? 'ltr' }}" {{ $current === $code ? 'selected' : '' }}>{{ $meta['name'] ?? $code }}</option>
        @endforeach
    </select>

    <noscript>
        <div class="mt-2">
            @foreach ($available as $code => $meta)
                <a href="{{ route('language.switch', $code) }}" class="inline-block px-2 py-1 text-xs text-slate-300">{{ $meta['name'] ?? $code }}</a>
            @endforeach
        </div>
    </noscript>
</div>
