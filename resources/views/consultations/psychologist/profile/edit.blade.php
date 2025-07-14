<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Pengaturan Profil Psikolog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('psychologist.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Kontainer utama dengan gaya kartu elegan --}}
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                    {{-- Bagian isi form --}}
                    <div class="p-6 md:p-8 space-y-8">
                        
                        {{-- Bagian Foto Profil --}}
                        <div>
                            <x-input-label for="profile_image" value="Foto Profil" class="text-base font-semibold text-gray-900"/>
                            <p class="text-sm text-gray-500 mt-1">Ganti foto profil Anda. Ukuran yang disarankan adalah persegi (1:1).</p>
                            <div class="mt-4 flex items-center gap-x-6">
                                @if ($profile->profile_image_path)
                                    <img src="{{ asset('storage/' . $profile->profile_image_path) }}" alt="Foto Profil" class="h-28 w-28 rounded-full object-cover shadow-md">
                                @else
                                    {{-- Placeholder jika tidak ada foto --}}
                                    <span class="h-28 w-28 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 border">
                                        <svg class="h-12 w-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    </span>
                                @endif
                                {{-- Input file yang sudah di-styling --}}
                                <input id="profile_image" name="profile_image" type="file" class="text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-brand-pink hover:file:bg-pink-100"/>
                            </div>
                             <x-input-error class="mt-2" :messages="$errors->get('profile_image')" />
                        </div>

                        {{-- Bagian Harga --}}
                        <div class="border-t pt-8">
                             @php
                                $inputClasses = 'mt-1 block w-full md:w-1/2 bg-slate-50 border-gray-300 text-black focus:border-brand-pink focus:ring-brand-pink rounded-md shadow-sm';
                            @endphp
                            <x-input-label for="price_per_hour" value="Harga per Jam (dalam Rupiah)" class="text-base font-semibold text-gray-900" />
                            <p class="text-sm text-gray-500 mt-1">Tarif ini akan ditampilkan kepada calon pasien saat akan membuat reservasi.</p>
                            {{-- Menggunakan <input> standar untuk konsistensi --}}
                            <input id="price_per_hour" name="price_per_hour" type="number" class="{{ $inputClasses }}" value="{{ old('price_per_hour', $profile->price_per_hour) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('price_per_hour')" />
                        </div>
                    </div>

                    {{-- Bagian footer form dengan tombol simpan --}}
                    <div class="bg-gray-50 px-8 py-4 flex items-center justify-end">
                         <button type="submit" class="inline-flex items-center px-6 py-2 brand-gradient text-white font-semibold rounded-full shadow-sm hover:opacity-90 transition-all transform hover:scale-105">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>