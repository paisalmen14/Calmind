@php
    // PERBAIKAN UTAMA DI SINI:
    // Tentukan NAMA ALIAS komponen, bukan path ke file view.
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
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Riwayat Konsultasi') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border border-gray-100">
        <div class="p-6 md:p-8">
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

            <div class="space-y-4">
                @forelse ($consultations as $consultation)
                    <div class="p-4 border rounded-lg flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div class="flex-grow">
                            @if(Auth::user()->role === 'pengguna')
                                <p class="font-bold text-gray-900">{{ $consultation->psychologist->name }}</p>
                                <p class="text-sm text-gray-500">Psikolog</p>
                            @else
                                <p class="font-bold text-gray-900">{{ $consultation->user->name }}</p>
                                <p class="text-sm text-gray-500">Pasien</p>
                            @endif
                        </div>
                        <div class="text-left sm:text-center">
                            <p class="text-sm text-gray-800">{{ $consultation->requested_start_time->isoFormat('dddd, D MMM YYYY') }}</p>
                            <p class="text-xs text-gray-500">{{ $consultation->requested_start_time->format('H:i') }} WIB</p>
                        </div>
                        <div class="text-left sm:text-center">
                             <span class="px-3 py-1 text-xs font-semibold text-white rounded-full
                                @switch($consultation->status)
                                    @case('confirmed') bg-blue-500 @break
                                    @case('completed') bg-green-500 @break
                                    @case('pending_payment') bg-yellow-500 @break
                                    @case('pending_verification') bg-orange-500 @break
                                    @case('payment_rejected') bg-red-500 @break
                                    @default bg-gray-500
                                @endswitch
                            ">
                                {{ str_replace('_', ' ', Str::title($consultation->status)) }}
                            </span>
                        </div>
                         <div class="text-left sm:text-right">
                             @can('view-chat-history', $consultation)
                                <a href="{{ route('chat.show', $consultation) }}" class="text-sm text-blue-600 hover:underline">Lihat Chat</a>
                             @endcan
                             @if($consultation->status == 'pending_payment' && Auth::user()->role == 'pengguna')
                                <a href="{{ route('consultations.payment.create', $consultation) }}" class="text-sm text-green-600 hover:underline ml-2">Bayar Sekarang</a>
                             @endif
                         </div>
                    </div>
                @empty
                    <div class="text-center py-10 px-6 border-2 border-dashed rounded-xl">
                        <p class="text-gray-500">Tidak ada riwayat konsultasi.</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-6">
                {{ $consultations->links() }}
            </div>
        </div>
    </div>
</x-dynamic-component>
