@props(['type' => 'default', 'size' => 'sm'])

@php
    $colors = [
        'default' => 'bg-gray-100 text-gray-800',
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'danger' => 'bg-red-100 text-red-800',
        'info' => 'bg-blue-100 text-blue-800',
        'indigo' => 'bg-indigo-100 text-indigo-800',
        'purple' => 'bg-purple-100 text-purple-800',
    ];
    $color = $colors[$type] ?? $colors['default'];

    $sizes = [
        'xs' => 'px-1.5 py-0.5 text-[10px]',
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-sm',
    ];
    $size = $sizes[$size] ?? $sizes['sm'];
@endphp

<span class="inline-flex items-center font-medium rounded-full {{ $color }} {{ $size }}">
    {{ $slot }}
</span>
