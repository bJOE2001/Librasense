@props([
    'label' => null,
    'name' => null,
    'value' => null,
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'class' => '',
])

<div class="col-span-6 sm:col-span-4">
    <x-ui.input
        type="range"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value }}"
        min="{{ $min }}"
        max="{{ $max }}"
        step="{{ $step }}"
        label="{{ $label }}"
        :required="$required"
        :disabled="$disabled"
        :error="$error"
        :help="$help"
        class="{{ $class }}"
    />
</div> 