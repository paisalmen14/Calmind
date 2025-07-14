@props(['disabled' => false])

{{-- Warna diubah agar sesuai dengan tema slate/dark --}}
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-slate-600 bg-slate-900 text-slate-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm']) !!}>