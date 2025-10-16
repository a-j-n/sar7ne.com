@props([
    'id' => null,
    'name' => null,
    'value' => '1',
    'label' => null,
    'description' => null,
    'error' => null,
    'disabled' => false,
    'class' => '',
])

@php
    $inputBase = 'h-5 w-5 rounded border-slate-300 bg-white text-emerald-600 '
        . 'focus:ring-emerald-500 focus:ring-offset-0 focus:outline-none '
        . 'dark:bg-slate-900/70 dark:border-slate-700/60 '
        . 'disabled:opacity-50 disabled:cursor-not-allowed';
    $labelBase = 'text-sm text-slate-900 dark:text-slate-100';
    $descBase = 'text-xs text-slate-500 dark:text-slate-400 mt-1';
    $errorText = 'text-xs text-red-600 dark:text-red-400 mt-1';
    $errorRing = $error ? ' border-red-500 focus:ring-red-500' : '';
@endphp

@if($label)
<label @if($id) for="{{ $id }}" @endif class="flex items-start gap-3 select-none">
    <input type="checkbox"
        @if($id) id="{{ $id }}" @endif
        @if($name) name="{{ $name }}" @endif
        value="{{ $value }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->except(['id','class','name','value'])->merge([
            'class' => trim($inputBase.$errorRing.' '.$class)
        ]) }} />
    <span>
        <span class="{{ $labelBase }}">{{ $label }}</span>
        @if($description)
            <div class="{{ $descBase }}">{{ $description }}</div>
        @endif
        @if($error)
            <div class="{{ $errorText }}">{{ $error }}</div>
        @endif
    </span>
</label>
@else
<input type="checkbox"
    @if($id) id="{{ $id }}" @endif
    @if($name) name="{{ $name }}" @endif
    value="{{ $value }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->except(['id','class','name','value'])->merge([
        'class' => trim($inputBase.$errorRing.' '.$class)
    ]) }} />
@endif

