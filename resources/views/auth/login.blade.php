<x-guest-layout>
    @section('page_title', 'Login - ' . config('app.name', 'Calmind'))
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
                    <h2 class="font-londrina text-3xl font-bold text-black">Selamat Datang Kembali!</h2>
                    <p class="font-quicksand mt-[2px] text-black">Masuk untuk melanjutkan perjalananmu.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Alamat Email -->
                    <div>
                        <x-input-label for="email" value="Email" class="font-gabarito" />
                        <x-text-input id="email" class="block mt-1 w-full bg-white border-black focus:border-black focus:ring-black" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" value="Password" class="font-gabarito" />
                        <x-text-input id="password" class="block mt-1 w-full bg-white border-black focus:border-black focus:ring-black"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me & Lupa Password -->
                    <div class="flex items-center justify-between mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-brand-pink shadow-sm focus:ring-brand-pink" name="remember">
                            <span class="ms-2 text-sm text-black font-gabarito">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-black font-gabarito hover:text-brand-pink rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-pink" href="{{ route('password.request') }}">
                                {{ __('Lupa password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Tombol Login -->
                    <div class="mt-6">
                        <button type="submit" class="w-full flex justify-center py-2 px-3 border border-transparent rounded-full shadow-sm text-base font-gabarito text-black bg-[#FFB4B4] hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-pink transition-all transform hover:scale-105">
                            {{ __('Log In') }}
                        </button>
                    </div>
                </form>

                <!-- Link untuk Register -->
                <div class="mt-8 text-center text-sm text-black font-gabarito">
                    <p class="mb-4">
                        Belum punya akun?
                    </p>
                    
                    <div class="flex flex-col sm:flex-row justify-center mt-6 gap-4">
                        <a href="{{ route('register') }}" 
                                class="px-3 py-2 bg-[#FFB4B4] text-black rounded-[11px] 
                                hover:opacity-70 transition-opacity whitespace-nowrap 
                                font-gabarito text-sm flex items-center justify-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            Daftar sebagai pengguna
                        </a>
                        
                        <a href="{{ route('psychologist.register') }}" 
                                class="px-5 py-2 bg-[#FFB4B4] text-black rounded-[11px] 
                                hover:opacity-70 transition-opacity whitespace-nowrap 
                                font-gabarito text-sm flex items-center justify-center gap-1">
                            <img src="/images/stethoscope.png" alt="Icon Psikolog" class="w-4 h-4">
                            Daftar sebagai psikolog
                        </a>
                    </div>
                </div>
            </div>            
        </div>
</x-guest-layout>
