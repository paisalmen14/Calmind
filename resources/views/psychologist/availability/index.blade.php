<x-layouts.psychologist>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Atur Jadwal Ketersediaan') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Kolom Kiri: Form Tambah Jadwal --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Jadwal Baru</h3>
                <form action="{{ route('psychologist.availability.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="start_time" value="Waktu Mulai" />
                        <x-text-input id="start_time" name="start_time" type="datetime-local" class="mt-1 block w-full" required />
                        @error('start_time') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label for="end_time" value="Waktu Selesai" />
                        <x-text-input id="end_time" name="end_time" type="datetime-local" class="mt-1 block w-full" required />
                        @error('end_time') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="text-right">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Kolom Kanan: Daftar Jadwal Tersedia --}}
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                 <h3 class="text-lg font-bold text-gray-800 mb-4">Jadwal Anda yang Akan Datang</h3>
                 @if(session('success'))
                    <div class="mb-4 bg-green-100 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded-md" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded-md" role="alert">
                         <p>{{ session('error') }}</p>
                    </div>
                @endif

                 <div class="space-y-3">
                    @forelse($availabilities as $availability)
                        <div class="p-4 rounded-lg flex justify-between items-center {{ $availability->is_booked ? 'bg-gray-200' : 'bg-blue-50' }}">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $availability->start_time->isoFormat('dddd, D MMMM YYYY') }}</p>
                                <p class="text-sm text-gray-600">{{ $availability->start_time->format('H:i') }} - {{ $availability->end_time->format('H:i') }} WIB</p>
                            </div>
                            <div>
                                @if($availability->is_booked)
                                    <span class="px-3 py-1 text-xs font-semibold text-white bg-yellow-500 rounded-full">Dipesan</span>
                                @else
                                    <form action="{{ route('psychologist.availability.destroy', $availability) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-4">Anda belum memiliki jadwal ketersediaan.</p>
                    @endforelse
                 </div>
            </div>
        </div>
    </div>
</x-layouts.psychologist>
