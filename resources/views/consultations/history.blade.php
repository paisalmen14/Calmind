@php
    // Tentukan layout mana yang akan digunakan berdasarkan peran pengguna
    $layoutComponent = 'app-layout'; // Default untuk pengguna biasa (nama aliasnya 'app-layout')
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            $layoutComponent = 'layouts.admin';
        } elseif (Auth::user()->role === 'psikolog') {
            $layoutComponent = 'layouts.psychologist';
        }
    }
@endphp

<x-dynamic-component :component="$layoutComponent">
    <x-slot name="header">
        {{-- Mengubah judul header sesuai gambar --}}
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Riwayat Konsultasi Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-6 md:p-8">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded-md" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                     @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-400 text-red-700 px-4 py-3 rounded-md" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="space-y-4">
                        @forelse ($consultations as $consultation)
                            <div class="p-5 border border-gray-200 rounded-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white shadow-sm">
                                <div class="flex-grow">
                                    {{-- Tampilan nama psikolog/pasien dan detail sesi --}}
                                    <p class="text-gray-600 text-sm">Konsultasi dengan</p>
                                    @if(Auth::user()->role === 'pengguna')
                                        <p class="font-bold text-gray-900 text-lg">{{ $consultation->psychologist->name }}</p>
                                    @else
                                        <p class="font-bold text-gray-900 text-lg">{{ $consultation->user->name }}</p>
                                    @endif
                                    <p class="text-sm text-gray-700 mt-1">
                                        {{ $consultation->requested_start_time->isoFormat('dddd, D MMMM YYYY - HH:mm') }} WIB
                                        ({{ $consultation->duration_minutes }} menit)
                                    </p>
                                </div>
                                
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                     {{-- Badge Status --}}
                                    <span class="px-3 py-1 text-xs font-semibold text-white rounded-full
                                        @switch($consultation->status)
                                            @case('confirmed') bg-green-500 @break
                                            @case('completed') bg-blue-500 @break
                                            @case('pending_payment') bg-yellow-500 @break
                                            @case('pending_verification') bg-orange-500 @break
                                            @case('payment_rejected') bg-red-500 @break
                                            @case('cancelled') bg-gray-500 @break
                                            @default bg-gray-500
                                        @endswitch
                                    ">
                                        {{ str_replace('_', ' ', Str::title($consultation->status)) }}
                                    </span>

                                    {{-- Tombol Aksi --}}
                                    @can('view-chat-history', $consultation)
                                        <a href="{{ route('chat.show', $consultation) }}" class="px-5 py-2 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 transition-colors text-sm">
                                            MASUK RUANG CHAT
                                        </a>
                                    @elsecan('access-consultation-chat', $consultation)
                                        {{-- Jika ini adalah sesi live chat yang sedang berlangsung --}}
                                        <a href="{{ route('chat.show', $consultation) }}" class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors text-sm">
                                            MASUK RUANG CHAT
                                        </a>
                                    @else
                                        {{-- Jika tidak ada akses chat (misal: sesi belum dimulai sama sekali) --}}
                                        <button disabled class="px-5 py-2 bg-gray-400 text-white font-semibold rounded-md text-sm cursor-not-allowed opacity-70">
                                            MASUK RUANG CHAT
                                        </button>
                                    @endcan

                                    @if($consultation->status == 'pending_payment' && Auth::user()->role == 'pengguna')
                                        <a href="{{ route('consultations.payment.create', $consultation) }}" class="px-5 py-2 bg-brand-pink text-white font-semibold rounded-md hover:opacity-90 transition-all text-sm">
                                            Bayar Sekarang
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 px-6 border-2 border-dashed rounded-xl text-gray-500">
                                <p>Tidak ada riwayat konsultasi.</p>
                                @if(Auth::user()->role === 'pengguna')
                                    <a href="{{ route('consultations.index') }}" class="mt-4 inline-block px-6 py-2 brand-gradient text-white font-semibold rounded-full hover:opacity-90 transition-all">
                                        Cari Psikolog
                                    </a>
                                @endif
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-6">
                        {{ $consultations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
