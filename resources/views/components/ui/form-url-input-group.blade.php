@props([
    'label' => null,
    'name' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'class' => '',
])

<div class="col-span-6 sm:col-span-4">
    <x-ui.input
        type="url"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value }}"
        label="{{ $label }}"
        :required="$required"
        :disabled="$disabled"
        :error="$error"
        :help="$help"
        class="{{ $class }}"
    />
</div> 