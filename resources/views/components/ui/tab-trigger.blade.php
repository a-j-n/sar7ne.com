@props(['value', 'class' => ''])

<button 
    x-on:click="activeTab = '{{ $value }}'"
    :class="activeTab === '{{ $value }}' ? 'bg-white dark:bg-slate-900/70 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
    {{ $attributes->merge(['class' => 'flex-1 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 focus:ring-offset-slate-100 dark:focus:ring-offset-white/10 ' . $class]) }}
    role="tab"
    :aria-selected="activeTab === '{{ $value }}'"
>
    {{ $slot }}
</button>
