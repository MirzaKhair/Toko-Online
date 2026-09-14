@props([
    'label' => '',
    'name' => '',
    'value' => '1',
    'checked' => false,
])

<div class="flex items-center">
    <input
        type="hidden"
        name="{{ $name }}"
        value="0"
    >
    <input
        type="checkbox"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value }}"
        {{ $checked ? 'checked' : '' }}
        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition"
    >
    <label for="{{ $name }}" class="ml-2 text-sm text-gray-700">
        {{ $label }}
    </label>
</div>
