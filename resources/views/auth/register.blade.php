<x-guest-layout>
    @section('page_title', 'Register - ' . config('app.name', 'Calmind'))
    <div class="grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_minmax(300px,450px)]">
            <!-- Kolom Kiri -->
            <div class="py-8 px-4 md:py-12 md:px-6  
                    min-h-[400px] flex flex-col justify-between">
            
                {{-- Judul Utama Kolom Kiri --}}
                <div class="mb-8">
                    <h2 class="font-londrina text-5xl font-extrabold text-black leading-tight">
                        Ruang Aman <br> Untuk Bercerita
                    </h2>
                </div>

                {{-- Bagian Fitur (Ruang Cerita Anonim & Self Journaling) --}}
                <div class="space-y-6">
                    
                    {{-- Fitur 1: Ruang Cerita Anonim --}}
                    <div class="flex items-start gap-4">
                        <img src="/images/anonymous-message.png" alt="Icon Ruang Cerita Anonim" class="w-14 h-14 flex-shrink-0">
                        <div>
                            <h4 class="font-quicksand text-lg font-bold text-black">Ruang Cerita Anonim</h4>
                            <p class="font-quicksand mt-1 text-sm text-black">
                                Bagikan perasaan dan pengalaman Anda dengan aman dan anonim. Dapatkan dukungan dari komunitas yang memahami.
                            </p>
                        </div>
                    </div>

                    {{-- Fitur 2: Self Journaling --}}
                    <div class="flex items-start gap-4">
                        <img src="/images/journal.png" alt="Icon Self Journaling" class="w-14 h-14 flex-shrink-0">
                        <div>
                            <h4 class="font-quicksand text-lg font-bold text-black">Self Journaling</h4>
                            <p class="font-quicksand mt-1 text-sm text-black">
                                Catat perjalanan emosional Anda setiap hari dan dapatkan umpan balik instan dari asisten AI kami.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Logo Calmind di Bagian Bawah --}}
                <div class="mt-auto pt-8">
                    <a href="/" class="flex items-center gap-3" aria-label="Calmind Homepage">
                        <x-application-logo class="w-8 h-8 text-brand-purple" />
                        <span class="font-serif text-2xl font-bold text-gray-900">Calmind</span>
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="pt-4 pb-6 px-6 md:pt-8 md:pb-12 md:px-12 bg-[#5EABD6] rounded-3xl soft-shadow">
                <!-- Judul Form -->
                <div class="text-center mb-8">
                    <h2 class="font-londrina text-3xl font-bold text-black">Jadilah Bagian dari Calmind!</h2>
                    <p class="font-quicksand mt-[2px] text-black">Daftar akun untuk memulai perjalananmu.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nama')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="username" :value="__('Username')" />
                        <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />

                        <x-text-input id="password" class="block mt-1 w-full"
                                        type="password"
                                        name="password"
                                        required autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

                        <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                        type="password"
                                        name="password_confirmation" required autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a class="underline text-sm text-black hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                            {{ __('Sudah Punya Akun?') }}
                        </a>

                        <x-primary-button class="ms-4 font-gabarito">
                            {{ __('Daftar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>            
        </div>
        
</x-guest-layout>