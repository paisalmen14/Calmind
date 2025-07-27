{{-- resources/views/stories/show.blade.php (Versi Elegan Baru) --}}
<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="mb-8">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-[#FFB4B4] text-black font-gabarito text-sm
                            rounded-full shadow-sm hover:bg-[#E14434] transition-colors whitespace-nowrap">
                    &larr; Kembali ke Ruang Cerita
                </a>
            </div>

            <div class="bg-[#5EABD6] p-8 rounded-2xl shadow-lg">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center font-bold text-xl text-gray-500">
                            {{ $story->is_anonymous ? 'A' : strtoupper(substr($story->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-gabarito text-xl text-black">
                                @if ($story->is_anonymous)
                                    <span class="text-black">Anonim</span>
                                @else
                                    {{ $story->user->name }}
                                @endif
                            </p>
                            <p class="text-xs font-gabarito text-black">
                                {{ $story->created_at->format('d F Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
                <p class="text-black text-sl leading-relaxed whitespace-pre-wrap">{{ $story->content }}</p>
            </div>

            {{-- Form Komentar --}}
            @if(Auth::user()->role !== 'admin')
                <div class="bg-[#5EABD6] p-8 rounded-2xl shadow-lg">
                    <h3 class="font-gabarito text-xl text-black mb-4">Tinggalkan Komentar</h3>
                    <form action="{{ route('comments.store', $story) }}" method="POST">
                        @csrf
                        <textarea name="content" rows="4" class="w-full bg-slate-50 rounded-lg shadow-sm focus:border-brand-purple focus:ring-brand-purple" placeholder="Tulis komentarmu di sini..."></textarea>
                        @error('content')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="px-6 py-2 bg-[#FFB4B4] text-black font-gabarito text-sm rounded-full hover:bg-[#E14434]">
                                Kirim Komentar
                            </button>
                        </div>
                    </form>
                </div>
            @endif

           {{-- Daftar Komentar --}}
           <div class="bg-[#5EABD6] p-8 rounded-2xl shadow-lg border">
                <h3 class="font-gabarito text-xl text-black mb-6">Komentar ({{ $story->comments->count() }})</h3>

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
                        <p class="text-gray-700 text-center py-4">Belum ada komentar. Jadilah yang pertama!</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>