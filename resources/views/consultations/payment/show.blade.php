<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-serif font-bold text-3xl text-gray-900">
                Reservasi Jadwal
            </h2>
            <a href="{{ route('consultations.index') }}" class="text-sm font-medium text-gray-600 hover:text-brand-pink flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Psikolog
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Kolom Kiri: Detail Profil Psikolog --}}
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 text-center sticky top-28">
                        @if($psychologist->psychologistProfile?->profile_image_path)
                            <img src="{{ asset('storage/' . $psychologist->psychologistProfile->profile_image_path) }}" alt="{{ $psychologist->name }}" class="w-32 h-32 rounded-full object-cover mx-auto mb-4 border-4 border-white shadow-lg">
                        @else
                            <div class="w-32 h-32 rounded-full bg-gray-200 mx-auto mb-4 flex items-center justify-center text-gray-400 border-4 border-white shadow-lg">
                                <svg class="h-16 w-16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            </div>
                       @endif
                       <h3 class="font-bold text-2xl text-gray-900">{{ $psychologist->name }}</h3>
                       <p class="text-base text-brand-pink font-medium">{{ $psychologist->psychologistProfile->specialization ?? 'Psikolog Umum' }}</p>
                       
                       <div class="mt-4 pt-4 border-t border-gray-100">
                           <p class="text-gray-500 text-sm">Tarif Mulai</p>
                           <p class="text-3xl font-bold text-gray-900">Rp{{ number_format($psychologist->psychologistProfile->price_per_hour ?? 0, 0, ',', '.') }}<span class="text-base font-medium text-gray-500">/jam</span></p>
                       </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Form Pemesanan --}}
                <div class="lg:col-span-2">
                     <div class="bg-white p-6 md:p-8 rounded-2xl shadow-xl border border-gray-100">
                        <h3 class="font-serif text-2xl font-bold text-gray-900 mb-1">Buat Jadwal Konsultasi</h3>
                        <p class="text-gray-500 mb-6">Pilih tanggal, waktu, dan durasi sesi Anda.</p>
                        
                        <form action="{{ route('consultations.reserve') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="psychologist_id" value="{{ $psychologist->id }}">
                            
                            @php
                                $selectClasses = 'block w-full mt-1 bg-slate-50 border-gray-300 text-black focus:border-brand-pink focus:ring-brand-pink rounded-md shadow-sm';
                            @endphp

                            @if($availableSlots->isNotEmpty())
                                <div>
                                    <x-input-label for="requested_start_time" value="Pilih Tanggal & Waktu Tersedia" class="font-semibold" />
                                    <select name="requested_start_time" id="requested_start_time" class="{{ $selectClasses }}" required>
                                        <option value="" disabled selected>-- Pilih Slot Waktu --</option>
                                        @foreach($availableSlots as $slot)
                                            <option value="{{ $slot->start_time->toDateTimeString() }}">
                                                {{ $slot->start_time->isoFormat('dddd, D MMMM YYYY - HH:mm') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="duration_minutes" value="Pilih Durasi Sesi" class="font-semibold" />
                                    <select name="duration_minutes" id="duration_minutes" class="{{ $selectClasses }}" required>
                                        <option value="30">30 Menit</option>
                                        <option value="60" selected>60 Menit (1 Jam)</option>
                                        <option value="90">90 Menit (1.5 Jam)</option>
                                    </select>
                                </div>
                                
                                <div class="pt-4 border-t border-gray-200 text-right">
                                    <button type="submit" class="inline-flex items-center px-6 py-3 brand-gradient text-white font-semibold rounded-full shadow-sm hover:opacity-90 transition-all transform hover:scale-105">
                                        Lanjutkan ke Pembayaran
                                    </button>
                                </div>
                            @else
                                {{-- Tampilan jika tidak ada jadwal tersedia --}}
                                <div class="text-center py-10 px-6 border-2 border-dashed rounded-xl">
                                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    <h3 class="mt-4 text-xl font-bold text-gray-800">Jadwal Tidak Tersedia</h3>
                                    <p class="mt-2 text-base text-gray-500">
                                        Saat ini, {{ $psychologist->name }} belum memiliki jadwal yang tersedia. Silakan cek kembali nanti atau pilih psikolog lain.
                                    </p>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>