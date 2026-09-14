@props(['padding' => true, 'shadow' => true, 'hover' => false])

@php
    $classes = 'bg-white rounded-xl border border-gray-100';
    $classes .= $shadow ? ' shadow-sm' : '';
    $classes .= $hover ? ' hover:shadow-md transition-shadow duration-200' : '';
    $classes .= $padding ? ' p-6' : '';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
