{{-- resources/views/articles/show.blade.php (Versi Elegan Baru) --}}
<x-app-layout>
    {{-- Header dikosongkan agar judul bisa lebih besar di dalam konten --}}
    <x-slot name="header">
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden rounded-2xl shadow-xl border border-gray-100">
                @if($article->image_path)
                    <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="w-full h-auto object-cover max-h-96">
                @endif
                <div class="p-8 md:p-10">
                    <h1 class="font-serif text-4xl font-bold text-gray-900 mb-3 leading-tight">{{ $article->title }}</h1>
                    <p class="text-sm text-gray-500 mb-8 border-b border-gray-200 pb-4">
                        Oleh: <span class="font-medium text-gray-800">{{ $article->user->name }}</span> &bull; Diposting pada {{ $article->created_at->format('d F Y') }}
                    </p>

                    {{-- Styling konten artikel agar lebih mudah dibaca --}}
                    <div class="prose max-w-none text-lg text-gray-800 leading-relaxed">
                        {!! nl2br(e($article->content)) !!}
                    </div>

                     <div class="mt-10 border-t border-gray-200 pt-6">
                        <a href="{{ route('articles.index') }}" class="font-medium text-brand-pink hover:underline">
                            &larr; Kembali ke semua artikel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>