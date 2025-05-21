@props([
    'label' => 'Reset',
    'type' => 'reset',
    'class' => '',
])

<button
    type="{{ $type }}"
    class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 {{ $class }}"
    {{ $attributes }}
>
    {{ $label }}
</button> 