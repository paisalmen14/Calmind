<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Konfirmasi Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Detail Reservasi --}}
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-xl border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Detail Reservasi Anda</h3>
                <div class="space-y-3 text-gray-700">
                    <div class="flex justify-between">
                        <span>Psikolog:</span>
                        <span class="font-semibold">{{ $consultation->psychologist->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Jadwal:</span>
                        <span class="font-semibold">{{ $consultation->requested_start_time->isoFormat('dddd, D MMMM YYYY, HH:mm') }} WIB</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Durasi:</span>
                        <span class="font-semibold">{{ $consultation->duration_minutes }} Menit</span>
                    </div>
                    <div class="flex justify-between border-t pt-3 mt-3">
                        <span class="font-bold">Total Pembayaran:</span>
                        <span class="font-bold text-brand-pink text-lg">Rp {{ number_format($consultation->total_payment, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Instruksi Pembayaran --}}
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-xl border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Instruksi Pembayaran</h3>
                <ol class="list-decimal list-inside text-gray-600 space-y-2">
                    <li>Lakukan transfer sebesar <strong>Rp {{ number_format($consultation->total_payment, 0, ',', '.') }}</strong> ke rekening berikut:</li>
                    <li class="ml-4 my-2 p-2 bg-gray-100 rounded-md">
                        <strong>Bank BCA: 123-456-7890</strong> a.n. PT Calmind Sejahtera
                    </li>
                    <li>Batas waktu pembayaran adalah <strong>1 jam</strong> dari sekarang.</li>
                    <li>Setelah transfer berhasil, unggah bukti pembayaran pada form di bawah ini.</li>
                </ol>
            </div>

            {{-- Form Konfirmasi --}}
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-xl border border-gray-100">
                 <h3 class="text-xl font-bold text-gray-800 mb-4">Form Konfirmasi</h3>
                <form action="{{ route('consultations.payment.store', $consultation) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
                    @csrf
                     <input type="hidden" name="amount" value="{{ $consultation->total_payment }}">
                    <div>
                        <x-input-label for="payment_date" :value="__('Tanggal Transfer')" />
                        <x-text-input id="payment_date" name="payment_date" type="date" class="mt-1 block w-full" :value="old('payment_date')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('payment_date')" />
                    </div>
                    <div>
                        <x-input-label for="proof" :value="__('Unggah Bukti Transfer (JPG, PNG)')" />
                        <input id="proof" name="proof" type="file" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-pink-50 file:text-brand-pink hover:file:bg-pink-100" required accept="image/png, image/jpeg" />
                        <x-input-error class="mt-2" :messages="$errors->get('proof')" />
                    </div>
                    <div class="flex items-center justify-end gap-4 border-t pt-6">
                        <a href="{{ route('consultations.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Batalkan</a>
                        <button type="submit" class="px-6 py-2 brand-gradient text-white font-semibold rounded-full hover:opacity-90 transition-all">
                            Kirim Konfirmasi
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
