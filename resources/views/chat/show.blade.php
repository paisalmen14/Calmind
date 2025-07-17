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
                    <div class="h-96 overflow-y-auto bg-slate-50 border border-gray-200 rounded-lg p-4 mb-4 space-y-4 flex flex-col"
                         x-data="chatData({{ json_encode($messages->toArray()) }}, {{ $consultation->id }})"
                         x-init="init()"
                         x-ref="chatbox">
                        <template x-for="message in messages" :key="message.id">
                            <div class="flex" :class="message.sender_id === {{ Auth::id() }} ? 'justify-end' : 'justify-start'">
                                <div class="max-w-md rounded-2xl p-4" :class="message.sender_id === {{ Auth::id() }} ? 'brand-gradient text-white' : 'bg-gray-200 text-gray-800'">
                                    <p class="whitespace-pre-wrap leading-relaxed" x-text="message.message"></p>
                                    <span class="text-xs" :class="message.sender_id === {{ Auth::id() }} ? 'text-white/70' : 'text-gray-500'" x-text="formatTime(message.created_at)"></span>
                                </div>
                            </div>
                        </template>
                        <div x-show="loading" class="flex justify-start">
                            <div class="max-w-xs rounded-2xl p-4 bg-gray-200 text-gray-800">
                                <p class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-pulse" style="animation-delay: 0s;"></span>
                                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-pulse" style="animation-delay: 0.2s;"></span>
                                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-pulse" style="animation-delay: 0.4s;"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Form Input Pesan --}}
                    <form @submit.prevent="sendMessage" x-data="chatData({{ json_encode($messages->toArray()) }}, {{ $consultation->id }})">
                        @csrf
                        <div class="flex space-x-3">
                            <textarea name="message" x-model="newMessage" rows="1" class="flex-grow border-gray-300 focus:border-brand-pink focus:ring-brand-pink rounded-full shadow-sm resize-none overflow-hidden" placeholder="{{ $isArchived ? 'Sesi chat diarsipkan.' : 'Ketik pesan Anda...' }}" {{ $isArchived ? 'disabled' : '' }}></textarea>
                            <button type="submit" class="px-6 py-2 brand-gradient text-white font-semibold rounded-full hover:opacity-90 transition-all transform hover:scale-105 disabled:opacity-50 disabled:scale-100" {{ $isArchived ? 'disabled' : '' }} :disabled="loading || !newMessage.trim()">
                                Kirim
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function chatData(initialMessages, consultationId) {
            return {
                messages: initialMessages,
                newMessage: '',
                loading: false,
                consultationId: consultationId,
                init() {
                    this.scrollToBottom();
                },
                scrollToBottom() {
                    this.$nextTick(() => {
                        if (this.$refs.chatbox) {
                            this.$refs.chatbox.scrollTop = this.$refs.chatbox.scrollHeight;
                        }
                    });
                },
                formatTime(timestamp) {
                    const date = new Date(timestamp);
                    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                },
                async sendMessage() {
                    if (this.newMessage.trim() === '') return;

                    this.loading = true;
                    const messageToSend = this.newMessage;
                    this.newMessage = ''; // Clear input immediately

                    // Add user's message to UI optimistically
                    const tempMessageId = Date.now(); // Temporary ID
                    this.messages.push({
                        id: tempMessageId,
                        sender_id: {{ Auth::id() }},
                        message: messageToSend,
                        created_at: new Date().toISOString(), // Use ISO string for consistency
                        is_temp: true // Mark as temporary
                    });
                    this.scrollToBottom();


                    try {
                        const response = await fetch(`{{ url('chat/consultation') }}/${this.consultationId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ message: messageToSend })
                        });

                        if (!response.ok) {
                            // If response is not OK, revert the optimistic update
                            this.messages = this.messages.filter(msg => msg.id !== tempMessageId);
                            const errorData = await response.json();
                            alert(errorData.message || 'Gagal mengirim pesan.');
                            return;
                        }

                        // Assuming successful response means message was saved,
                        // we don't need to add it again, it will be fetched by a real-time system
                        // or on next page load. For now, we keep the optimistic update.
                        // To remove the 'is_temp' flag, you might need to re-fetch or get the real ID from the backend.
                        // For this example, we'll just remove the temp flag and assume success.
                        this.messages = this.messages.map(msg => {
                            if (msg.id === tempMessageId) {
                                delete msg.is_temp; // Remove temporary flag
                                // In a real app, you'd update with the actual ID from the server response
                            }
                            return msg;
                        });

                    } catch (error) {
                        console.error('Error:', error);
                        // Revert the optimistic update on error
                        this.messages = this.messages.filter(msg => msg.id !== tempMessageId);
                        alert('Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.');
                    } finally {
                        this.loading = false;
                        this.scrollToBottom();
                    }
                }
            }
        }
    </script>
</x-dynamic-component>
