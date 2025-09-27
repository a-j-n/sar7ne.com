@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'flex space-x-1 rounded-2xl bg-slate-100 dark:bg-slate-900/60 p-1 ' . $class]) }} role="tablist">
    {{ $slot }}
</div>
