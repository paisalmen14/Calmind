{{-- resources/views/profile/edit.blade.php (Versi Elegan Baru) --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            {{-- Kartu untuk Informasi Profil --}}
            <div class="p-6 md:p-8 bg-white shadow-xl sm:rounded-2xl border border-gray-100">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Kartu untuk Ganti Password --}}
            <div class="p-6 md:p-8 bg-white shadow-xl sm:rounded-2xl border border-gray-100">
                @include('profile.partials.update-password-form')
            </div>

            {{-- Kartu untuk Hapus Akun --}}
            <div class="p-6 md:p-8 bg-white shadow-xl sm:rounded-2xl border border-gray-100">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>