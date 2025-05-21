@props(['for' => null, 'required' => false])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }} {{ $for ? 'for="' . $for . '"' : '' }}>
    {{ $slot }}
    @if($required)
        <span class="text-red-500">*</span>
    @endif
</label> 