@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center space-x-3 px-4 py-3 text-white bg-gray-900'
            : 'flex items-center space-x-3 px-4 py-3 text-gray-400 hover:bg-gray-700 hover:text-white transition-colors duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
