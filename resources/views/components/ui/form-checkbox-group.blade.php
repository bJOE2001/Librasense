@props([
    'label' => null,
    'name' => null,
    'value' => null,
    'checked' => false,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'class' => '',
])

<div class="col-span-6 sm:col-span-4">
    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input
                type="checkbox"
                name="{{ $name }}"
                id="{{ $name }}"
                value="{{ $value }}"
                {{ $checked ? 'checked' : '' }}
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded"
                {{ $attributes }}
            >
        </div>
        <div class="ml-3 text-sm">
            <label for="{{ $name }}" class="font-medium text-gray-700">{{ $label }}</label>
            @if($help)
                <p class="text-gray-500">{{ $help }}</p>
            @endif
            @if($error)
                <p class="text-red-600">{{ $error }}</p>
            @endif
        </div>
    </div>
</div> 