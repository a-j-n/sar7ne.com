@php
    $current = app()->getLocale();
    $available = config('locales.available', []);
    $currentMeta = $available[$current] ?? null;
    $currentName = $currentMeta['name'] ?? strtoupper($current);
@endphp

<div
    x-data="{
        open: false,
        change(url, dir) {
            document.documentElement.setAttribute('dir', dir || 'ltr');
            window.location.href = url;
        }
    }"
    x-on:keydown.escape.window="open = false"
    class="relative"
>
    <button
        type="button"
        @click="open = !open"
        class="group flex items-center gap-2 rounded-full border border-secondary dark:border-white/10 bg-white/70 dark:bg-slate-900/80 px-3 py-1.5 text-xs font-semibold text-secondary dark:text-slate-100 shadow-sm hover:shadow-md transition-all duration-200"
        aria-haspopup="true"
        :aria-expanded="open.toString()"
    >
        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gradient-blue-mint text-white shadow-sm group-hover:scale-105 transition">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-3.5 w-3.5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12h19.5M12 2.25c2.485 2.3 4.05 5.663 4.05 9.75s-1.565 7.45-4.05 9.75c-2.485-2.3-4.05-5.663-4.05-9.75s1.565-7.45 4.05-9.75z" />
            </svg>
        </span>
        <span class="flex flex-col leading-tight text-left">
            <span class="text-[11px] uppercase tracking-wide text-muted dark:text-slate-400">{{ __('messages.language') }}</span>
            <span class="text-sm font-semibold text-primary dark:text-white">{{ $currentName }}</span>
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
            <p class="px-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted dark:text-slate-500">{{ __('messages.language') }}</p>

            @foreach ($available as $code => $meta)
                @php
                    $isActive = $code === $current;
                @endphp
                <button
                    type="button"
                    @click.prevent="change('{{ route('language.switch', $code) }}', '{{ $meta['dir'] ?? 'ltr' }}')"
                    class="group flex w-full items-center gap-3 rounded-xl border border-transparent px-3 py-2.5 text-left transition-all duration-200 {{ $isActive ? 'bg-gradient-blue-mint/10 border-brand-blue/40 shadow-glow glow-neon-mint/30' : 'hover:bg-secondary/80 dark:hover:bg-white/10' }}"
                >
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/70 dark:bg-slate-900/70 shadow-inner">
                        <span class="text-sm font-semibold text-brand-orange dark:text-brand-mint">{{ strtoupper($code) }}</span>
                    </span>
                    <span class="flex flex-col text-left">
                        <span class="text-sm font-semibold text-primary dark:text-white">{{ $meta['name'] ?? strtoupper($code) }}</span>
                        <span class="text-xs text-muted dark:text-slate-400">{{ $meta['locale'] ?? strtoupper($code) }}</span>
                    </span>
                    <span class="ml-auto text-brand-orange opacity-0 transition-opacity duration-200 group-hover:opacity-70 {{ $isActive ? 'opacity-100' : '' }}">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    <noscript>
        <div class="absolute right-0 top-full mt-2 flex gap-1 rounded-lg bg-primary dark:bg-slate-900 p-1 shadow-lg">
            @foreach ($available as $code => $meta)
                <a href="{{ route('language.switch', $code) }}" class="px-2 py-1 text-xs font-semibold {{ $code === $current ? 'text-brand-orange' : 'text-secondary dark:text-slate-300' }}">{{ $meta['name'] ?? $code }}</a>
            @endforeach
        </div>
    </noscript>
</div>
