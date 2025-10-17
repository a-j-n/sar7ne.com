@props(['text', 'class' => '', 'size' => 'sm'])

<button
    x-data="{ copied: false }"
    x-on:click="
        navigator.clipboard.writeText('{{ $text }}').then(() => {
            copied = true;
            setTimeout(() => copied = false, 1600);
        });
    "
    x-on:keydown.enter.prevent="
        navigator.clipboard.writeText('{{ $text }}').then(() => {
            copied = true;
            setTimeout(() => copied = false, 1600);
        });
    "
    type="button"
    :aria-label="copied ? '{{ __('Copied!') }}' : '{{ __('Copy to clipboard') }}'"
    :title="copied ? '{{ __('Copied!') }}' : '{{ __('Copy to clipboard') }}'"
    {{ $attributes->merge(['class' => 'relative inline-flex items-center gap-2 rounded-md px-2 py-1 text-sm font-medium ring-1 ring-transparent hover:ring-black/5 dark:hover:ring-white/10 hover:bg-black/5 dark:hover:bg-white/5 transition-colors ' . $class]) }}
>
    <span x-show="!copied" class="flex items-center gap-2" x-transition.opacity.duration.150ms>
        <!-- Butter-smooth Copy SVG (duotone) -->
        <svg class="size-4 text-black/70 dark:text-white/70" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M9 3h8a2 2 0 0 1 2 2v8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
            <rect x="5" y="7" width="12" height="12" rx="2.5" class="fill-current opacity-10"/>
            <rect x="5" y="7" width="12" height="12" rx="2.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
        </svg>
        {{ $slot ?: __('Copy') }}
    </span>
    <span x-show="copied" x-transition.opacity.duration.150ms class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400">
        <!-- Butter-smooth Check SVG -->
        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M20 6L9 17l-5-5"/>
        </svg>
        {{ __('Copied!') }}
    </span>
</button>
