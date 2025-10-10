@props([
    'label' => '',
    'error' => '',
    'type' => 'text',
    'required' => false,
    'class' => '',
    'id' => ''
])

<div class="space-y-2">
    @if($label)
        <label @if($id) for="{{ $id }}" @endif class="block text-sm font-medium text-secondary">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}"
        @if($id) id="{{ $id }}" @endif
        {{ $attributes->except(['label', 'error', 'required', 'id'])->merge([
            'class' => 'w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-black placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/20 transition-colors ' . $class
        ]) }}
    />
    
    @if($error)
        <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
