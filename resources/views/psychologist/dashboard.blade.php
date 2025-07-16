<x-layouts.psychologist>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Dashboard Psikolog') }}
        </h2>
    </x-slot>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Konsultasi Mendatang</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['upcoming_consultations'] }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-4a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Konsultasi Selesai</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['completed_consultations'] }}</p>
            </div>
        </div>
    </div>

    {{-- Tabel Konsultasi Mendatang --}}
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Jadwal Konsultasi Terdekat</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Pasien</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($upcomingConsultations as $consultation)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $consultation->user->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $consultation->requested_start_time->isoFormat('dddd, D MMMM YYYY') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $consultation->requested_start_time->format('H:i') }} WIB</td>
                        <td class="px-4 py-3 text-sm text-blue-600">
                            <a href="{{ route('chat.show', $consultation) }}" class="hover:underline">Masuk Ruang Chat</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4 text-gray-500">Tidak ada jadwal konsultasi mendatang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.psychologist>
