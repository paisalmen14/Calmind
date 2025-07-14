<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upgrade Membership') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900">Keuntungan Member</h3>
                <ul class="mt-4 list-disc list-inside text-gray-600">
                    <li>Akses chat tanpa batas dengan psikolog profesional.</li>
                    <li>Dukungan prioritas.</li>
                </ul>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900">Langkah Pembayaran</h3>
                <ol class="mt-4 list-decimal list-inside text-gray-600 space-y-2">
                    <li>Lakukan transfer sebesar <strong>Rp 100.000,-</strong> untuk 1 bulan keanggotaan.</li>
                    <li>Nomor Rekening: <strong>BCA 123-456-7890 a.n. PT Sehat Jiwa</strong></li>
                    <li>Setelah transfer berhasil, isi form konfirmasi di bawah ini.</li>
                </ol>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900">Form Konfirmasi Pembayaran</h3>
                <form action="{{ route('membership.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
                    @csrf
                    <div>
                        <x-input-label for="amount" :value="__('Jumlah Transfer')" />
                        <x-text-input id="amount" name="amount" type="number" class="mt-1 block w-full" :value="old('amount', '100000')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                    </div>

                    <div>
    <x-input-label for="psychologist_id" :value="__('Pilih Psikolog Anda')" />
    <select id="psychologist_id" name="psychologist_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
        <option value="">-- Pilih seorang psikolog --</option>
        @forelse ($psychologists as $psychologist)
            <option value="{{ $psychologist->id }}" {{ old('psychologist_id') == $psychologist->id ? 'selected' : '' }}>
                {{ $psychologist->name }}
            </option>
        @empty
            <option value="" disabled>Saat ini tidak ada psikolog yang tersedia.</option>
        @endforelse
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('psychologist_id')" />
</div>

                     <div>
                        <x-input-label for="payment_date" :value="__('Tanggal Transfer')" />
                        <x-text-input id="payment_date" name="payment_date" type="date" class="mt-1 block w-full" :value="old('payment_date')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('payment_date')" />
                    </div>
                    <div>
                        <x-input-label for="proof" :value="__('Bukti Transfer (JPG, PNG)')" />
                        <input id="proof" name="proof" type="file" class="mt-1 block w-full" required accept="image/png, image/jpeg" />
                        <x-input-error class="mt-2" :messages="$errors->get('proof')" />
                    </div>
                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Kirim Konfirmasi') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>