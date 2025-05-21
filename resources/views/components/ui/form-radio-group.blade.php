@props([
    'label' => null,
    'name' => null,
    'options' => [],
    'selected' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'class' => '',
])

<div class="col-span-6 sm:col-span-4">
    <div>
        @if($label)
            <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
        @endif
        <div class="mt-2 space-y-2">
            @foreach($options as $value => $label)
                <div class="flex items-center">
                    <input
                        type="radio"
                        name="{{ $name }}"
                        id="{{ $name }}_{{ $value }}"
                        value="{{ $value }}"
                        {{ $selected == $value ? 'checked' : '' }}
                        {{ $required ? 'required' : '' }}
                        {{ $disabled ? 'disabled' : '' }}
                        class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300"
                        {{ $attributes }}
                    >
                    <label for="{{ $name }}_{{ $value }}" class="ml-3 block text-sm font-medium text-gray-700">
                        {{ $label }}
                    </label>
                </div>
            @endforeach
        </div>
        @if($help)
            <p class="mt-2 text-sm text-gray-500">{{ $help }}</p>
        @endif
        @if($error)
            <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
        @endif
    </div>
</div> 