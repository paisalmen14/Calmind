<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-200 leading-tight">
            {{ __('Pengaturan Harga') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('psychologist.price.update') }}">
                @csrf
                @method('PUT')
                <div class="bg-slate-800 shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="p-6 md:p-8 text-slate-100 space-y-6">
                        <div>
                            <x-input-label for="price_per_hour" :value="__('Harga per Jam (dalam Rupiah)')" />
                            <p class="text-sm text-slate-400 mt-1 mb-2">Tarif ini akan ditampilkan kepada calon pasien saat akan membuat reservasi.</p>
                            <x-text-input id="price_per_hour" name="price_per_hour" type="number" class="mt-1 block w-full md:w-1/2" :value="old('price_per_hour', $profile->price_per_hour)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('price_per_hour')" />
                        </div>
                    </div>

                    <div class="bg-slate-700/50 px-6 py-4 flex items-center justify-end">
                        <x-primary-button>{{ __('Simpan Harga') }}</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>