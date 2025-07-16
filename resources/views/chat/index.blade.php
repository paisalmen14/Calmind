@php
    // Determine which layout to use based on the user's role
    $layout = Auth::user()->role === 'psikolog' ? 'layouts.psychologist' : 'layouts.app';
@endphp

<x-dynamic-component :component="$layout">
    <x-slot name="header">
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Ruang Chat') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
        <div class="p-6 md:p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Sesi Konsultasi Anda</h3>
            <div class="space-y-4">
                @forelse ($consultations as $consultation)
                    <a href="{{ route('chat.show', $consultation) }}" class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border transition-all">
                        <div class="flex justify-between items-center">
                            <div>
                                @if(Auth::user()->role === 'pengguna')
                                    <p class="font-bold text-gray-900">{{ $consultation->psychologist->name }}</p>
                                    <p class="text-sm text-gray-500">Psikolog</p>
                                @else
                                    <p class="font-bold text-gray-900">{{ $consultation->user->name }}</p>
                                    <p class="text-sm text-gray-500">Pasien</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-800">{{ $consultation->requested_start_time->isoFormat('dddd, D MMM YYYY') }}</p>
                                <p class="text-xs text-gray-500">{{ $consultation->requested_start_time->format('H:i') }} WIB</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-10 px-6 border-2 border-dashed rounded-xl">
                        <p class="text-gray-500">Anda tidak memiliki sesi chat yang aktif saat ini.</p>
                        @if(Auth::user()->role === 'pengguna')
                            <a href="{{ route('consultations.index') }}" class="mt-4 inline-block px-6 py-2 brand-gradient text-white font-semibold rounded-full hover:opacity-90 transition-all">
                                Cari Psikolog
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-dynamic-component>
