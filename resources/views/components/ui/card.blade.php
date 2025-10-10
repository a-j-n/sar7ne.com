@props(['class' => '', 'padding' => 'p-6'])

<div {{ $attributes->merge(['class' => 'rounded-3xl border border-slate-200 dark:border-white/10 bg-white text-black text-black-on-white dark:bg-gradient-to-br dark:text-inherit dark:from-white/10 dark:to-slate-900/10 shadow-xl backdrop-blur-sm ' . $padding . ' ' . $class]) }}>
    {{ $slot }}
</div>
