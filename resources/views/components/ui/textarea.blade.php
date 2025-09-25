@props([
    'label' => '',
    'error' => '',
    'rows' => 4,
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
    
    <textarea 
        rows="{{ $rows }}"
        @if($id) id="{{ $id }}" @endif
        {{ $attributes->except(['label', 'error', 'required', 'rows', 'id'])->merge([
            'class' => 'w-full rounded-xl border border-secondary bg-primary px-4 py-3 text-sm text-primary placeholder:text-muted focus:border-brand-orange focus:outline-none focus:ring-2 focus:ring-brand-orange/20 transition-colors resize-vertical ' . $class
        ]) }}
    >{{ $slot }}</textarea>
    
    @if($error)
        <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
