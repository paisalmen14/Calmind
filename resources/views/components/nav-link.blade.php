@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#E14434] text-m font-gabarito leading-5 text-black focus:outline-none focus:border-[#FFB4B4] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-m font-gabarito leading-5 text-[#333333] hover:text-black hover:border-[#E14434] focus:outline-none focus:text-black focus:border-[#FFB4B4] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
