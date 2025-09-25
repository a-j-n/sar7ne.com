@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'flex space-x-1 rounded-2xl bg-slate-100 dark:bg-white/10 p-1 ' . $class]) }} role="tablist">
    {{ $slot }}
</div>
