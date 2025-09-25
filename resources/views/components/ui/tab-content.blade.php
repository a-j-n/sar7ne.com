@props(['value', 'class' => ''])

<div 
    x-show="activeTab === '{{ $value }}'"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    {{ $attributes->merge(['class' => 'mt-6 ' . $class]) }}
    role="tabpanel"
>
    {{ $slot }}
</div>
