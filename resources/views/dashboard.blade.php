<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-serif font-bold text-3xl text-gray-900">
                    {{ __('Ruang Cerita') }}
                </h2>
                <p class="mt-1 text-gray-600">Bagikan ceritamu dan temukan dukungan dari komunitas.</p>
            </div>
            <a href="{{ route('stories.create') }}" class="inline-block px-6 py-2 brand-gradient text-white font-semibold rounded-full shadow-sm hover:opacity-90 transition-all">
                + Tulis Cerita Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 text-green-800 p-4 rounded-md shadow-sm">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- Filter dan Pencarian --}}
            <div class="mb-6 flex justify-between items-center">
                <div class="flex space-x-4">
                    <a href="{{ route('dashboard', ['filter' => 'newest']) }}" class="{{ request('filter', 'newest') == 'newest' ? 'text-brand-purple font-bold border-b-2 border-brand-purple' : 'text-gray-500' }} transition-colors pb-1">Terbaru</a>
                    <a href="{{ route('dashboard', ['filter' => 'top']) }}" class="{{ request('filter') == 'top' ? 'text-brand-purple font-bold border-b-2 border-brand-purple' : 'text-gray-500' }} transition-colors pb-1">Top</a>
                    <a href="{{ route('dashboard', ['filter' => 'popular']) }}" class="{{ request('filter') == 'popular' ? 'text-brand-purple font-bold border-b-2 border-brand-purple' : 'text-gray-500' }} transition-colors pb-1">Populer</a>
                </div>
                <form action="{{ route('dashboard') }}" method="GET">
                     <input type="text" name="search" placeholder="Cari cerita..." class="border-gray-300 rounded-full focus:ring-brand-purple focus:border-brand-purple shadow-sm text-sm" value="{{ request('search') }}">
                </form>
            </div>

            <div class="space-y-6">
                @forelse ($stories as $story)
                    <div class="bg-white overflow-hidden rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8">
                        <div class="flex items-start justify-between">
                             <div>
                                <p class="font-bold text-lg text-gray-800">
                                    @if ($story->is_anonymous || !$story->user)
                                        <span class="text-gray-600">Anonim</span>
                                    @else
                                        <a href="{{ route('profile.show', $story->user) }}" class="hover:underline">{{ $story->user->name }}</a>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">{{ $story->created_at->diffForHumans() }}</p>
                            </div>
                            @can('update-story', $story)
                                <a href="{{ route('stories.edit', $story) }}" class="text-sm text-blue-500 hover:underline">Edit</a>
                            @endcan
                        </div>
                        <a href="{{ route('stories.show', $story) }}" class="block mt-4">
                            <p class="text-gray-800 text-lg leading-relaxed">{{ Str::limit($story->content, 300) }}</p>
                        </a>
                        <div class="mt-6 flex items-center space-x-6 border-t pt-4">
                            {{-- Tombol Votes --}}
                            <div class="flex items-center space-x-2 text-gray-500">
                                {{-- ================================== --}}
                                {{-- PERBAIKAN UTAMA ADA DI BLOK INI --}}
                                {{-- ================================== --}}
                                <form action="{{ route('stories.vote', $story) }}" method="POST" class="inline vote-form">
                                    @csrf
                                    <input type="hidden" name="vote" value="up">
                                    <button type="submit" class="hover:text-green-500 transition-colors flex items-center gap-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                        <span class="text-sm font-medium" id="upvotes-count-{{ $story->id }}">{{ $story->upvotes_count }}</span>
                                    </button>
                                </form>
                                <form action="{{ route('stories.vote', $story) }}" method="POST" class="inline vote-form">
                                    @csrf
                                    <input type="hidden" name="vote" value="down">
                                    <button type="submit" class="hover:text-red-500 transition-colors flex items-center gap-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        <span class="text-sm font-medium" id="downvotes-count-{{ $story->id }}">{{ $story->downvotes_count }}</span>
                                    </button>
                                </form>
                            </div>
                             {{-- Komentar --}}
                            <a href="{{ route('stories.show', $story) }}" class="flex items-center space-x-2 text-gray-500 hover:text-brand-purple transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                <span>{{ $story->comments->count() }} Komentar</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white text-center p-12 rounded-2xl shadow-lg border border-gray-100">
                        <p class="text-gray-600">Belum ada cerita yang dibagikan. Jadilah yang pertama!</p>
                    </div>
                @endforelse
            </div>

            {{-- Paginasi --}}
            <div class="mt-8">
                {{ $stories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>