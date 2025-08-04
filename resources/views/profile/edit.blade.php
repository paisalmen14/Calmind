@php
    // Logika untuk menentukan layout berdasarkan peran pengguna.
    // Default layout diubah kembali ke 'app-layout' yang benar.
    $layoutComponent = 'app-layout'; // Default layout
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            $layoutComponent = 'layouts.admin';
        } elseif (Auth::user()->role === 'psikolog') {
            $layoutComponent = 'layouts.psychologist';
        }
    }
@endphp

<x-dynamic-component :component="$layoutComponent">
    {{-- Judul halaman adalah 'Profil' --}}
    <x-slot name="header">
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Profil') }}
        </h2>
    </x-slot>

    {{-- Konten halaman berisi form untuk mengedit profil --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Form untuk memperbarui informasi profil --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{-- Menggunakan partial view yang sudah ada untuk form update profil --}}
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Form untuk memperbarui password --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{-- Menggunakan partial view yang sudah ada untuk form update password --}}
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Opsi untuk menghapus akun --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{-- Menggunakan partial view yang sudah ada untuk form hapus akun --}}
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
