<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif font-bold text-3xl text-gray-900">
                {{ __('Diary Harian') }}
            </h2>
            <a href="{{ route('daily-diary.create') }}" class="inline-block px-6 py-2 brand-gradient text-white font-semibold rounded-full shadow-sm hover:opacity-90 transition-all">
                + Tulis Diary
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-400 text-green-800 p-4 rounded-md">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('info'))
                <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 text-blue-800 p-4 rounded-md">
                    <p>{{ session('info') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-400 text-red-800 p-4 rounded-md">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="space-y-6">
                @forelse ($diaries as $diary)
                    <div class="bg-white p-6 rounded-2xl card-shadow border border-gray-100">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-bold text-xl text-gray-900">{{ $diary->title }}</h3> {{-- Tampilkan Judul --}}
                                <p class="text-sm text-gray-500">{{ $diary->entry_date->isoFormat('dddd, D MMMM YYYY') }}</p> {{-- Tanggal di bawah judul --}}
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('daily-diary.edit', $diary) }}" class="text-sm font-medium text-brand-pink hover:underline">Edit</a>
                                <form action="{{ route('daily-diary.destroy', $diary) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus entri diary ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                        <p class="mt-2 text-gray-800 line-clamp-3">{{ $diary->content }}</p>
                        <a href="{{ route('daily-diary.show', $diary) }}" class="mt-3 inline-block text-sm font-medium text-gray-600 hover:text-brand-pink">Baca Selengkapnya &rarr;</a>
                    </div>
                @empty
                    <div class="bg-white p-12 rounded-2xl card-shadow border border-gray-100 text-center">
                        <p class="text-gray-500">Anda belum menulis diary. Mulailah hari ini!</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-8">
                {{ $diaries->links() }}
            </div>

            {{-- Bagian "Lihat Rangkuman Mingguan" yang telah dihapus --}}
        </div>
    </div>
</x-app-layout>