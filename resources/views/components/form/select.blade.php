@props([
    'label' => '',
    'name' => '',
    'placeholder' => 'Pilih...',
    'required' => false,
    'disabled' => false,
    'options' => [],
])

@php
    $inputClasses = 'w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none disabled:bg-gray-50 disabled:text-gray-500';
@endphp

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => $inputClasses]) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ old($name, $attributes->get('selected')) == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
        {{ $slot }}
    </select>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
