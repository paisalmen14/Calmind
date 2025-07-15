{{-- resources/views/consultations/index.blade.php (Versi Elegan Baru) --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-serif font-bold text-3xl text-gray-900">
                    {{ __('Konsultasi Profesional') }}
                </h2>
                <p class="mt-1 text-gray-600">Pilih psikolog yang paling sesuai untuk menemani perjalanan Anda.</p>
            </div>
            <form action="{{ route('consultations.index') }}" method="GET">
                <input type="text" name="search" placeholder="Cari nama psikolog..." class="border-gray-300 rounded-full focus:ring-brand-pink focus:border-brand-pink shadow-sm" value="{{ request('search') }}">
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($psychologists as $psychologist)
                    <div class="bg-white overflow-hidden rounded-2xl card-shadow border border-gray-100 flex flex-col text-center group">
                        <div class="p-8">
                            {{-- Foto Profil Psikolog --}}
                            @if($psychologist->psychologistProfile?->profile_image_path)
                                <img src="{{ asset('storage/' . $psychologist->psychologistProfile->profile_image_path) }}" alt="{{ $psychologist->name }}" class="w-32 h-32 rounded-full object-cover mx-auto mb-4 border-4 border-white shadow-lg">
                            @else
                                <div class="w-32 h-32 rounded-full bg-gray-200 mx-auto mb-4 flex items-center justify-center text-gray-400 border-4 border-white shadow-lg">
                                    <svg class="h-16 w-16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                </div>
                           @endif

                           <h3 class="font-bold text-xl text-gray-900">{{ $psychologist->name }}</h3>
                           <p class="text-sm text-brand-pink font-medium">{{ $psychologist->psychologistProfile->specialization ?? 'Psikolog Umum' }}</p>
                           
                           <div class="mt-4 pt-4 border-t border-gray-100">
                               <p class="text-gray-500 text-sm">Mulai dari</p>
                               <p class="text-3xl font-bold text-gray-900">Rp{{ number_format($psychologist->psychologistProfile->price_per_hour ?? 0, 0, ',', '.') }}<span class="text-base font-medium text-gray-500">/sesi</span></p>
                           </div>
                        </div>
                        <div class="p-5 bg-gray-50 mt-auto">
                           <a href="{{ route('consultations.psychologist.show', $psychologist) }}" class="w-full text-center inline-block bg-gray-800 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-900 transition-colors">
                               Lihat Profil & Jadwal
                           </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-12 rounded-2xl card-shadow border border-gray-100 text-center">
                        <h3 class="font-semibold text-lg text-gray-800">Tidak Ada Psikolog</h3>
                        <p class="text-gray-500 mt-1">Saat ini tidak ada psikolog yang tersedia atau cocok dengan pencarian Anda.</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-8">
                {{ $psychologists->links() }}
            </div>
        </div>
    </div>
</x-app-layout>