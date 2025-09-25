@props(['text', 'class' => '', 'size' => 'sm'])

<button 
    x-data="{ copied: false }"
    x-on:click="
        navigator.clipboard.writeText('{{ $text }}').then(() => {
            copied = true;
            setTimeout(() => copied = false, 2000);
        });
    "
    {{ $attributes->merge(['class' => 'relative inline-flex items-center gap-2 transition-all duration-200 ' . $class]) }}
>
    <span x-show="!copied" class="flex items-center gap-2">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
        {{ $slot ?: __('Copy') }}
    </span>
    <span x-show="copied" x-transition class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ __('Copied!') }}
    </span>
</button>
