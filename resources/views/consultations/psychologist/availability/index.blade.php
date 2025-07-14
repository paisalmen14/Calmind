<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profil {{ $user->name }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h3 class="text-2xl font-bold">{{ $user->name }}</h3>
                <p class="text-gray-600">{{'@'}}{{ $user->username }}</p>
                <p class="mt-2">Bergabung pada {{ $user->created_at->format('d F Y') }}</p>
            </div>

            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-200">Cerita dari {{ $user->name }}</h3>
            <div class="space-y-4">
                @forelse($stories as $story)
                    {{-- Anda bisa membuat komponen terpisah untuk card story agar tidak duplikat kode --}}
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <p>{{ $story->content }}</p>
                        <div class="text-xs text-gray-500 mt-2">
                            Diposting {{ $story->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <p>{{ $user->name }} belum membagikan cerita publik.</p>
                    </div>
                @endforelse
            </div>
             <div class="mt-6">
                {{ $stories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>