<x-guest-layout>
    <form method="POST" action="{{ route('psychologist.register') }}" enctype="multipart/form-data">
        @csrf

        {{-- Judul Form yang Disesuaikan --}}
        <div class="text-center mb-8">
            <h2 class="font-londrina text-5xl font-bold text-black">Pendaftaran Psikolog</h2>
            <p class="mt-2 text-black font-quicksand">Lengkapi data diri dan profesional Anda untuk bergabung.</p>
        </div>

        @php
            // Definisikan kelas styling untuk input di satu tempat agar mudah diubah
            $inputClasses = 'block mt-1 w-full bg-slate-50 border-gray-300 text-black focus:border-brand-pink focus:ring-brand-pink rounded-md shadow-sm';
        @endphp

        {{-- Grup field data diri --}}
        <div class="space-y-6">
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" class="font-semibold text-gray-900" />
                <x-text-input id="name" class="{{ $inputClasses }}" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="username" :value="__('Username')" class="font-semibold text-gray-900" />
                <x-text-input id="username" class="{{ $inputClasses }}" type="text" name="username" :value="old('username')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="font-semibold text-gray-900" />
                <x-text-input id="email" class="{{ $inputClasses }}" type="email" name="email" :value="old('email')" required autocomplete="email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>

        {{-- Grup field data profesional --}}
        <div class="mt-6 pt-6 border-t border-gray-200 space-y-6">
             <div>
                <x-input-label for="ktp_number" :value="__('Nomor KTP')" class="font-semibold text-gray-900" />
                <x-text-input id="ktp_number" class="{{ $inputClasses }}" type="text" name="ktp_number" :value="old('ktp_number')" required />
                <x-input-error :messages="$errors->get('ktp_number')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="ktp_image" :value="__('Foto KTP (JPG, PNG)')" class="font-semibold text-gray-900" />
                <input id="ktp_image" name="ktp_image" type="file" class="block w-full text-sm text-gray-700 mt-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-brand-pink hover:file:bg-pink-100" required />
                <x-input-error :messages="$errors->get('ktp_image')" class="mt-2" />
            </div>

            <div>
                 <x-input-label for="university" :value="__('Lulusan Universitas')" class="font-semibold text-gray-900" />
                <x-text-input id="university" class="{{ $inputClasses }}" type="text" name="university" :value="old('university')" required />
                <x-input-error :messages="$errors->get('university')" class="mt-2" />
            </div>

             <div>
                 <x-input-label for="graduation_year" :value="__('Tahun Lulus')" class="font-semibold text-gray-900" />
                <x-text-input id="graduation_year" class="{{ $inputClasses }}" type="number" name="graduation_year" :value="old('graduation_year')" required />
                <x-input-error :messages="$errors->get('graduation_year')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="certificate" :value="__('Scan Ijazah atau Sertifikat (PDF, JPG, PNG)')" class="font-semibold text-gray-900" />
                <input id="certificate" name="certificate" type="file" class="block w-full text-sm text-gray-700 mt-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-brand-pink hover:file:bg-pink-100" required />
                <x-input-error :messages="$errors->get('certificate')" class="mt-2" />
            </div>
        </div>

        {{-- Grup field password --}}
        <div class="mt-6 pt-6 border-t border-gray-200 space-y-6">
            <div>
                <x-input-label for="password" :value="__('Password')" class="font-semibold text-gray-900" />
                <x-text-input id="password" class="{{ $inputClasses }}" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="font-semibold text-gray-900" />
                <x-text-input id="password_confirmation" class="{{ $inputClasses }}" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        {{-- Tombol dan Tautan Aksi --}}
        <div class="mt-8">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-base font-gabarito text-black bg-[#FFB4B4] hover:opacity-70 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-all transform hover:scale-105">
                {{ __('Daftar sebagai Psikolog') }}
            </button>
        </div>
        
        <div class="mt-6 text-center">
             <p class="text-sm font-gabarito text-black">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="ml-1 font-gabarito bg-[#FFB4B4] px-3 py-2 rounded-[11px] text-black hover:opacity-70">
                    Masuk di sini
                </a>
             </p>
        </div>
    </form>
</x-guest-layout>