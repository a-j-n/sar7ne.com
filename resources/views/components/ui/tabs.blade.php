@props(['defaultTab' => null])

<div x-data="{ activeTab: '{{ $defaultTab }}' }" class="w-full">
    {{ $slot }}
</div>
