<x-price :amount="$amount" :size="$size ?? 'md'" />
@props(['amount', 'size' => 'md'])

@php
    $sizeClasses = [
        'sm' => 'text-sm',
        'md' => 'text-base',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
        '2xl' => 'text-2xl'
    ];
    $textSize = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<span {{ $attributes->merge(['class' => "$textSize font-medium"]) }}>
    @currency($amount)
</span>
