@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'flex items-center justify-end ' . $class]) }}>
    {{ $slot }}
</div> 