@props([
    'variant' => 'primary',
    'size' => 'md',
    'class' => ''
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$variants = [
    'primary' => 'btn-brand-primary text-white focus:ring-brand-orange shadow-lg hover:shadow-xl',
    'secondary' => 'btn-brand-secondary text-white focus:ring-electric-blue shadow-lg hover:shadow-xl',
    'outline' => 'border border-brand-orange bg-transparent hover:bg-brand-orange/10 text-brand-orange focus:ring-brand-orange',
    'ghost' => 'bg-transparent hover:bg-brand-orange/10 text-brand-orange focus:ring-brand-orange',
    'danger' => 'bg-vivid-pink hover:bg-vivid-pink/90 text-white focus:ring-vivid-pink shadow-lg hover:shadow-xl',
    'mint' => 'bg-neon-mint hover:bg-neon-mint/90 text-white focus:ring-neon-mint shadow-lg hover:shadow-xl glow-neon-mint',
    'electric' => 'bg-electric-blue hover:bg-electric-blue/90 text-white focus:ring-electric-blue shadow-lg hover:shadow-xl'
];

$sizes = [
    'xs' => 'px-2 py-1 text-[11px] rounded-lg',
    'sm' => 'px-3 py-1.5 text-xs rounded-lg',
    'md' => 'px-4 py-2.5 text-sm rounded-xl',
    'lg' => 'px-6 py-3 text-base rounded-xl',
    'xl' => 'px-8 py-4 text-lg rounded-2xl'
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size] . ' ' . $class;
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
