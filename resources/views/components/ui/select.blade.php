@props([
    'id' => null,
    'class' => '',
])

<select @if($id) id="{{ $id }}" @endif
    {{ $attributes->except(['id','class'])->merge([
        'class' => (
            'w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-black '
            . 'hover:border-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 '
            . 'transition-colors disabled:opacity-50 disabled:cursor-not-allowed '
            . 'dark:bg-slate-900 dark:text-slate-100 dark:border-slate-700 '
            . 'dark:focus:border-emerald-500 dark:focus:ring-emerald-500/20'
        ) . ' ' . $class
    ]) }}>
    {{ $slot }}
</select>

