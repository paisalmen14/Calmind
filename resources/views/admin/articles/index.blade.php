{{-- resources/views/admin/articles/index.blade.php (Versi Elegan Baru) --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif font-bold text-3xl text-gray-900">
                {{ __('Manajemen Artikel') }}
            </h2>
            <a href="{{ route('admin.articles.create') }}" class="inline-block px-6 py-2 brand-gradient text-white font-semibold rounded-full shadow-sm hover:opacity-90 transition-all">
                + Buat Artikel Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border border-gray-100">
                <div class="p-6 md:p-8">
                    @if (session('success'))
                        <div class="mb-4 bg-green-50 border-l-4 border-green-400 text-green-800 p-4 rounded-md">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gambar</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($articles as $article)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="h-12 w-12 object-cover rounded-md">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($article->title, 50) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $article->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-4">
                                            <a href="{{ route('admin.articles.edit', $article) }}" class="text-brand-pink hover:underline">Edit</a>
                                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            Belum ada artikel.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $articles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>