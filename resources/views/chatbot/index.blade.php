{{-- resources/views/chatbot/index.blade.php (Versi Elegan Baru) --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif font-bold text-3xl text-gray-900">
                {{ __('Konsultasi AI') }}
            </h2>
            {{-- Tombol untuk menghapus history --}}
            <div x-data="chatBot" x-init="init()">
                <button @click="confirmClearHistory()" class="text-sm font-medium text-gray-600 hover:text-red-600 focus:outline-none transition-colors">
                    Hapus History
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-6 md:p-8 text-gray-900">

                    <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 rounded-lg">
                        <p class="font-bold">Penting!</p>
                        <p class="text-sm">Anda sedang berbicara dengan AI. Interaksi ini bukan pengganti konsultasi profesional dan riwayat percakapan disimpan di peramban Anda. Jika Anda berada dalam situasi krisis, segera hubungi tenaga kesehatan mental.</p>
                    </div>

                    {{-- Komponen Chat dengan Alpine.js dan style baru --}}
                    <div x-data="chatBot" x-init="init()">
                        {{-- Kotak Chat --}}
                        <div class="h-96 overflow-y-auto bg-slate-50 border border-gray-200 rounded-lg p-4 mb-4 space-y-4" x-ref="chatbox">
                            <template x-for="message in messages" :key="message.id">
                                <div class="flex" :class="message.role === 'user' ? 'justify-end' : 'justify-start'">
                                    <div class="max-w-md rounded-2xl p-4" :class="message.role === 'user' ? 'brand-gradient text-white' : 'bg-gray-200 text-gray-800'">
                                        <p class="whitespace-pre-wrap leading-relaxed" x-html="message.text"></p>
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
                        <form @submit.prevent="sendMessage">
                            <div class="flex space-x-3">
                                <input type="text" x-model="userInput" placeholder="Ketik pesan Anda di sini..." class="flex-grow border-gray-300 focus:border-brand-pink focus:ring-brand-pink rounded-full shadow-sm" :disabled="loading" autocomplete="off">
                                <button type="submit" class="px-6 py-2 brand-gradient text-white font-semibold rounded-full hover:opacity-90 transition-all transform hover:scale-105 disabled:opacity-50 disabled:scale-100" :disabled="loading">
                                    Kirim
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Script Alpine.js untuk fungsionalitas chatbot (tetap sama) --}}
    <script>
    function chatBot() {
        return {
            userInput: '',
            loading: false,
            messageId: 0,
            chatHistoryKey: `safetalk_chat_history_{{ auth()->id() }}`,
            initialMessage: { id: 1, role: 'bot', text: 'Halo! Aku SafeTalk, teman curhat virtualmu. Apa yang sedang kamu rasakan saat ini?' },
            messages: [],
            init() {
                const savedHistory = localStorage.getItem(this.chatHistoryKey);
                this.messages = savedHistory ? JSON.parse(savedHistory) : [this.initialMessage];
                this.messageId = this.messages.length > 0 ? Math.max(...this.messages.map(m => m.id)) : 1;
                this.scrollToBottom();
            },
            saveHistory() {
                localStorage.setItem(this.chatHistoryKey, JSON.stringify(this.messages));
            },
            confirmClearHistory() {
                if (confirm('Apakah Anda yakin ingin menghapus seluruh riwayat percakapan di peramban ini?')) {
                    localStorage.removeItem(this.chatHistoryKey);
                    location.reload();
                }
            },
            scrollToBottom() {
                this.$nextTick(() => {
                    this.$refs.chatbox.scrollTop = this.$refs.chatbox.scrollHeight;
                });
            },
            sendMessage() {
                if (this.userInput.trim() === '') return;
                this.messageId++;
                this.messages.push({ id: this.messageId, role: 'user', text: this.userInput });
                this.saveHistory();
                this.scrollToBottom();
                const userMessageToSend = this.userInput;
                this.userInput = '';
                this.loading = true;
                const historyForApi = this.messages.slice(1, -1).map(msg => ({
                    role: msg.role === 'user' ? 'user' : 'model',
                    text: msg.text
                }));
                fetch('{{ route("chatbot.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        message: userMessageToSend,
                        history: historyForApi
                    })
                })
                .then(response => {
                    if (!response.ok) { throw new Error('Network response was not ok'); }
                    return response.json();
                })
                .then(data => {
                    this.loading = false;
                    if(data.reply) {
                        this.messageId++;
                        this.messages.push({ id: this.messageId, role: 'bot', text: data.reply });
                        this.saveHistory();
                        this.scrollToBottom();
                    } else if (data.error) {
                        this.handleError(data.error);
                    }
                })
                .catch(error => {
                    this.handleError('Maaf, terjadi kesalahan saat mengirim pesan. Silakan coba lagi.');
                    console.error('Error:', error);
                });
            },
            handleError(errorMessage) {
                this.loading = false;
                this.messageId++;
                this.messages.push({ id: this.messageId, role: 'bot', text: errorMessage });
                this.saveHistory();
                this.scrollToBottom();
            }
        }
    }
    </script>
</x-app-layout>