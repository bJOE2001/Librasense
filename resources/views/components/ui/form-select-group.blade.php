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
    <x-ui.select
        name="{{ $name }}"
        id="{{ $name }}"
        :options="$options"
        :selected="$selected"
        label="{{ $label }}"
        :required="$required"
        :disabled="$disabled"
        :error="$error"
        :help="$help"
        class="{{ $class }}"
    />
</div> 