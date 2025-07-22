@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-gabarito text-sm text-black']) }}>
    {{ $value ?? $slot }}
</label>
