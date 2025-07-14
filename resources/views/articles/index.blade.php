<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Artikel Kesehatan Mental') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($articles as $article)
                    {{-- Kartu untuk setiap artikel --}}
                    <div class="bg-white overflow-hidden rounded-2xl card-shadow border border-gray-100 flex flex-col group">
                        <a href="{{ route('articles.show', $article) }}">
                            <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        </a>
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="font-bold text-lg text-gray-900 mb-2 leading-tight">
                                <a href="{{ route('articles.show', $article) }}" class="hover:text-brand-pink transition-colors">
                                    {{-- Batasi judul agar tidak terlalu panjang --}}
                                    {{ Str::limit($article->title, 60) }}
                                </a>
                            </h3>
                            <div class="text-sm text-gray-500 mb-4">
                                Diposting {{ $article->created_at->diffForHumans() }}
                            </div>
                            <p class="text-gray-700 text-sm line-clamp-3 flex-grow">
                                {{ Str::limit(strip_tags($article->content), 150) }}
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('articles.show', $article) }}" class="font-semibold text-brand-pink hover:underline">
                                    Baca Selengkapnya &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white p-12 rounded-2xl card-shadow border border-gray-100 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                        </svg>
                        <h3 class="mt-4 text-xl font-bold text-gray-800">Belum Ada Artikel</h3>
                        <p class="mt-2 text-base text-gray-500">Saat ini belum ada artikel yang dipublikasikan. Silakan cek kembali nanti.</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>