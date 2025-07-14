{{-- resources/views/stories/show.blade.php (Versi Elegan Baru) --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif font-bold text-3xl text-gray-900">
                {{ __('Detail Cerita') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-brand-pink">
                &larr; Kembali ke Ruang Cerita
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Kartu Cerita Utama --}}
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center font-bold text-xl text-gray-500">
                            {{ $story->is_anonymous ? 'A' : strtoupper(substr($story->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-lg text-gray-900">
                                @if ($story->is_anonymous)
                                    <span class="text-gray-700">Anonim</span>
                                @else
                                    {{ $story->user->name }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $story->created_at->format('d F Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
                <p class="text-gray-800 text-lg leading-relaxed whitespace-pre-wrap">{{ $story->content }}</p>
            </div>

            {{-- Form Komentar --}}
            @if(Auth::user()->role !== 'admin')
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <h3 class="font-serif text-2xl font-bold text-gray-900 mb-4">Tinggalkan Komentar</h3>
                    <form action="{{ route('comments.store', $story) }}" method="POST">
                        @csrf
                        <textarea name="content" rows="4" class="w-full bg-slate-50 border-gray-300 rounded-lg shadow-sm focus:border-brand-pink focus:ring-brand-pink" placeholder="Tulis komentarmu di sini..."></textarea>
                        @error('content')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="px-6 py-2 brand-gradient text-white font-semibold rounded-full hover:opacity-90 transition-all transform hover:scale-105">
                                Kirim Komentar
                            </button>
                        </div>
                    </form>
                </div>
            @endif

           {{-- Daftar Komentar --}}
           <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                <h3 class="font-serif text-2xl font-bold text-gray-900 mb-6">Komentar ({{ $story->comments->count() }})</h3>

                @if (session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-400 text-green-800 p-4 rounded-md">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <div class="space-y-6">
                    @forelse ($story->comments->whereNull('parent_id') as $comment)
                        {{-- Komponen partial untuk comment perlu disesuaikan juga --}}
                        @include('partials._comment', ['comment' => $comment, 'story' => $story])
                    @empty
                        <p class="text-gray-500 text-center py-4">Belum ada komentar. Jadilah yang pertama!</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>