{{-- resources/views/profile/partials/update-password-form.blade.php (Versi Elegan Baru) --}}
<section>
    <header>
        <h2 class="text-2xl font-serif font-bold text-gray-900">
            {{ __('Ganti Password') }}
        </h2>

        <p class="mt-2 text-sm text-gray-600">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" value="Password Saat Ini" class="font-semibold text-gray-700" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full bg-white border-gray-300 focus:border-brand-pink focus:ring-brand-pink text-gray-900 rounded-md" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" value="Password Baru" class="font-semibold text-gray-700" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full bg-white border-gray-300 focus:border-brand-pink focus:ring-brand-pink text-gray-900 rounded-md" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" value="Konfirmasi Password" class="font-semibold text-gray-700" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full bg-white border-gray-300 focus:border-brand-pink focus:ring-brand-pink text-gray-900 rounded-md" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
             <button type="submit" class="px-5 py-2 brand-gradient text-white font-semibold rounded-full hover:opacity-90 transition-all text-sm">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>