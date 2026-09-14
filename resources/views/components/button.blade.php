@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'block' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

    $variants = [
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
        'secondary' => 'bg-gray-200 text-gray-700 hover:bg-gray-300 focus:ring-gray-400',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'danger-outline' => 'border border-red-300 text-red-600 hover:bg-red-50 focus:ring-red-500',
        'ghost' => 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:ring-gray-400',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
        'warning' => 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-400',
    ];
    $variantClass = $variants[$variant] ?? $variants['primary'];

    $sizes = [
        'xs' => 'px-2.5 py-1 text-xs',
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];

    $blockClass = $block ? 'w-full' : '';
    $allClasses = trim("{$baseClasses} {$variantClass} {$sizeClass} {$blockClass}");
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $allClasses }}" {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" class="{{ $allClasses }}" {{ $attributes }}>
        {{ $slot }}
    </button>
@endif
