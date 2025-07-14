<x-guest-layout>
    <div class="w-full sm:max-w-4xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <!-- Kolom Kiri: Form Login -->
            <div class="p-6 md:p-8">
                <!-- Judul Form -->
                <div class="text-center md:text-left mb-8">
                    <h2 class="font-serif text-3xl font-bold text-gray-900">Selamat Datang Kembali</h2>
                    <p class="mt-2 text-gray-600">Masuk untuk melanjutkan perjalanan Anda.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Alamat Email -->
                    <div>
                        <x-input-label for="email" value="Email" class="font-semibold" />
                        <x-text-input id="email" class="block mt-1 w-full bg-slate-50 border-gray-300 focus:border-brand-pink focus:ring-brand-pink" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" value="Password" class="font-semibold" />
                        <x-text-input id="password" class="block mt-1 w-full bg-slate-50 border-gray-300 focus:border-brand-pink focus:ring-brand-pink"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me & Lupa Password -->
                    <div class="flex items-center justify-between mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-brand-pink shadow-sm focus:ring-brand-pink" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-brand-pink rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-pink" href="{{ route('password.request') }}">
                                {{ __('Lupa password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Tombol Login -->
                    <div class="mt-6">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-base font-medium text-white brand-gradient hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-pink transition-all transform hover:scale-105">
                            {{ __('Log In') }}
                        </button>
                    </div>
                </form>

                <!-- Link untuk Register -->
                <div class="mt-8 text-center text-sm text-gray-600">
                    <p>
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-medium text-brand-pink hover:underline">
                            Daftar sebagai pengguna
                        </a>
                    </p>
                    <p class="mt-2">
                        atau
                        <a href="{{ route('psychologist.register') }}" class="font-medium text-brand-pink hover:underline">
                            daftar sebagai psikolog
                        </a>
                    </p>
                </div>
            </div>

            <!-- Kolom Kanan: Gambar atau Pesan Selamat Datang -->
            <div class="hidden md:flex flex-col items-center justify-center bg-gray-50 rounded-r-lg p-8">
                <x-application-logo class="w-20 h-20 text-brand-pink" />
                <h3 class="mt-6 font-serif text-2xl font-bold text-center text-gray-800">Ruang Aman untuk Bercerita</h3>
                <p class="mt-2 text-center text-gray-500">
                    Setiap langkah kecil menuju penyembuhan adalah sebuah kemajuan besar.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
