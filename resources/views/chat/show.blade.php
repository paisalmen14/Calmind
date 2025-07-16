@php
    // Variabel $layout sudah diteruskan dari controller
    // Ini akan menentukan layout mana yang akan digunakan (layouts.app atau layouts.psychologist)
@endphp

<x-dynamic-component :component="$layout">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif font-bold text-3xl text-gray-900">
                {{ __('Chat dengan') }} {{ $contact->name }}
            </h2>
            <a href="{{ route('chat.index') }}" class="text-sm font-medium text-gray-600 hover:text-brand-pink flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Chat
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-6 md:p-8 text-gray-900">

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-800 rounded-lg">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @if ($isArchived)
                        <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-800 rounded-lg">
                            <p class="font-bold">Riwayat Chat</p>
                            <p class="text-sm">Sesi ini sudah berakhir atau belum dimulai. Anda hanya dapat melihat riwayat percakapan.</p>
                        </div>
                    @endif

                    {{-- Chat Box --}}
                    <div class="h-96 overflow-y-auto bg-slate-50 border border-gray-200 rounded-lg p-4 mb-4 space-y-4 flex flex-col" x-data="{ messages: [], init() { this.messages = @json($messages->toArray()); this.$nextTick(() => this.$refs.chatbox.scrollTop = this.$refs.chatbox.scrollHeight); } }" x-ref="chatbox">
                        @forelse ($messages as $message)
                            <div class="flex @if($message->sender_id === Auth::id()) justify-end @else justify-start @endif">
                                <div class="max-w-md rounded-2xl p-4 @if($message->sender_id === Auth::id()) brand-gradient text-white @else bg-gray-200 text-gray-800 @endif">
                                    <p class="whitespace-pre-wrap leading-relaxed">{{ $message->message }}</p>
                                    <span class="text-xs @if($message->sender_id === Auth::id()) text-white/70 @else text-gray-500 @endif block mt-1 text-right">{{ $message->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="flex-grow flex items-center justify-center text-gray-500 text-center">
                                <p>Belum ada pesan dalam sesi ini.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Form Input Pesan --}}
                    <form action="{{ route('chat.store', $consultation) }}" method="POST">
                        @csrf
                        <div class="flex space-x-3">
                            <textarea name="message" rows="1" class="flex-grow border-gray-300 focus:border-brand-pink focus:ring-brand-pink rounded-full shadow-sm resize-none overflow-hidden" placeholder="{{ $isArchived ? 'Sesi chat diarsipkan.' : 'Ketik pesan Anda...' }}" {{ $isArchived ? 'disabled' : '' }}></textarea>
                            <button type="submit" class="px-6 py-2 brand-gradient text-white font-semibold rounded-full hover:opacity-90 transition-all transform hover:scale-105 disabled:opacity-50 disabled:scale-100" {{ $isArchived ? 'disabled' : '' }}>
                                Kirim
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
