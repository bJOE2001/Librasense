@props([
    'name' => null,
    'value' => null,
])

<input
    type="hidden"
    name="{{ $name }}"
    id="{{ $name }}"
    value="{{ $value }}"
    {{ $attributes }}
/> 