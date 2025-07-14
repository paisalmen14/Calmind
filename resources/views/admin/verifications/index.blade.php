<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Verifikasi Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-6 md:p-8">
                     <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Pembayaran Menunggu Verifikasi</h3>
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    <div class="space-y-6">
                        @forelse ($consultations as $consultation)
                            <div class="p-4 border rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Pasien</p>
                                        <p class="font-bold text-gray-800">{{ $consultation->user->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Psikolog</p>
                                        <p class="font-bold text-gray-800">{{ $consultation->psychologist->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Total Bayar</p>
                                        <p class="font-bold text-gray-800">Rp {{ number_format($consultation->total_payment, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t">
                                    <a href="{{ asset('storage/' . $consultation->paymentConfirmation->proof_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Bukti Pembayaran</a>
                                </div>
                                <div class="mt-4 flex items-center space-x-4">
                                    <form action="{{ route('admin.consultation.verifications.approve', $consultation) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">Setujui</button>
                                    </form>
                                    {{-- Form untuk menolak --}}
                                    <form action="{{ route('admin.consultation.verifications.reject', $consultation) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="text" name="rejection_reason" placeholder="Alasan Penolakan (wajib diisi)" class="text-sm rounded-md border-gray-300">
                                        <button type="submit" class="ml-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">Tolak</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 px-6 border-2 border-dashed rounded-xl">
                                <p class="text-gray-500">Tidak ada pembayaran yang perlu diverifikasi.</p>
                            </div>
                        @endforelse
                    </div>
                     <div class="mt-8">
                        {{ $consultations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
