@props(['value'])

{{-- Warna teks diubah menjadi abu-abu tua agar cocok di background terang --}}
<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>