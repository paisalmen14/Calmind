<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Sukses --}}
            @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 text-green-800 p-4 rounded-md shadow-sm">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            {{-- Filter dan Pencarian --}}
            <div class="mb-6 flex justify-between items-center px-4 md:px-8 lg:px-12">
                <a href="{{ route('stories.create') }}" class="whitespace-nowrap inline-block px-6 py-2 bg-[#FFB4B4] text-sm text-black font-gabarito rounded-full shadow-sm hover:bg-[#E14434] transition-all">
                    + Tulis Cerita Baru
                </a>

                <div class="flex items-center gap-4">
                    <x-dropdown align="center" width="44">
                        <x-slot name="trigger">
                            {{-- Tombol Trigger Dropdown --}}
                            <button class="flex items-center justify-between w-32 px-6 py-2 bg-[#FFB4B4] text-black font-gabarito text-sm rounded-full shadow-sm hover:bg-[#E14434] transition-opacity whitespace-nowrap">
                                @php
                                $filterMap = [
                                'newest' => 'Terbaru',
                                'top' => 'Teratas',
                                'popular' => 'Terpopuler',
                                ];
                                $currentFilterKey = request('filter', 'newest');
                                $displayText = $filterMap[$currentFilterKey] ?? ucfirst($currentFilterKey);
                                @endphp
                                {{-- Teks Filter Aktif --}}
                                <span class="me-2">{{ $displayText }}</span>
                                {{-- Icon Panah Dropdown --}}
                                <svg class="ms-1 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <a href="{{ route('dashboard', ['filter' => 'newest']) }}"
                                class="block px-4 py-2 text-sm leading-5 font-gabarito transition-colors {{ request('filter', 'newest') == 'newest' ? ' text-black' : 'text-gray-700 hover:text-[#E14434]' }}">
                                Terbaru
                            </a>
                            <a href="{{ route('dashboard', ['filter' => 'top']) }}"
                                class="block px-4 py-2 text-sm leading-5 font-gabarito transition-colors {{ request('filter') == 'top' ? ' text-black' : 'text-gray-700 hover:text-[#E14434]' }}">
                                Terfavorit
                            </a>
                            <a href="{{ route('dashboard', ['filter' => 'popular']) }}"
                                class="block px-4 py-2 text-sm leading-5 font-gabarito transition-colors {{ request('filter') == 'popular' ? ' text-black' : 'text-gray-700 hover:text-[#E14434]' }}">
                                Terpopuler
                            </a>
                        </x-slot>
                    </x-dropdown>

                    <form action="{{ route('dashboard') }}" method="GET">
                        <input type="text" name="search" placeholder="Cari cerita..." class="bg-[#FFB4B4] w-60 py-2 font-gabarito text-black rounded-full shadow-sm border-0 focus:border-0 focus:ring-0 text-sm text-opacity-40" value="{{ request('search') }}">
                    </form>
                </div>
            </div>

            <div class="space-y-6">
                @forelse ($stories as $story)
                <div class="bg-[#5EABD6] overflow-hidden rounded-2xl shadow-lg p-6 md:p-8">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xl text-black font-gabarito">
                                @if ($story->is_anonymous || !$story->user)
                                <span class="text-black">Anonim</span>
                                @else
                                <a href="{{ route('profile.show', $story->user) }}" class="hover:underline">{{ $story->user->name }}</a>
                                @endif
                            </p>
                            <p class="text-xs font-gabarito text-black">{{ $story->created_at->diffForHumans() }}</p>
                        </div>
                        @can('update-story', $story)
                        <a href="{{ route('stories.edit', $story) }}" class="text-sm text-black hover:underline">Edit</a>
                        @endcan
                    </div>

                    <a href="{{ route('stories.show', $story) }}" class="block mt-4">
                        <p class="text-black text-sl leading-relaxed">{{ Str::limit($story->content, 300) }}</p>
                    </a>

                    <div class="mt-6 flex items-center space-x-6 pt-4">
                        <div class="flex items-center space-x-2 font-gabarito text-black">
                            <form action="{{ route('stories.vote', $story) }}" method="POST" class="inline vote-form">
                                @csrf
                                <input type="hidden" name="vote" value="up">
                                <button type="submit" class="hover:text-green-700 transition-colors flex items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                    <span class="text-sm font-medium" id="upvotes-count-{{ $story->id }}">{{ $story->upvotes_count }}</span>
                                </button>
                            </form>
                            <form action="{{ route('stories.vote', $story) }}" method="POST" class="inline vote-form">
                                @csrf
                                <input type="hidden" name="vote" value="down">
                                <button type="submit" class="hover:text-red-500 transition-colors flex items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                    <span class="text-sm font-medium" id="downvotes-count-{{ $story->id }}">{{ $story->downvotes_count }}</span>
                                </button>
                            </form>
                        </div>
                        {{-- Komentar --}}
                        <a href="{{ route('stories.show', $story) }}" class="flex items-center space-x-2 text-black font-gabarito hover:text-brand-purple transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
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