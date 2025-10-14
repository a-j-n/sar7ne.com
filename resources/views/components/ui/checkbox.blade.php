@props([
    'id' => null,
    'class' => '',
])

<input type="checkbox" @if($id) id="{{ $id }}" @endif
    {{ $attributes->except(['id','class'])->merge([
        'class' => (
            'h-4 w-4 rounded border-slate-300 bg-white text-emerald-600 '
            . 'focus:ring-emerald-500 focus:ring-offset-0 focus:outline-none '
            . 'dark:bg-slate-900/70 dark:border-slate-700/60 '
        ) . ' ' . $class
    ]) }} />

