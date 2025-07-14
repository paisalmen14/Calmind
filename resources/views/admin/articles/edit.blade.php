{{-- resources/views/admin/articles/edit.blade.php (Versi Elegan Baru) --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Edit Artikel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="p-8 space-y-6">
                        <div>
                            <x-input-label for="title" value="Judul Artikel" class="font-semibold text-gray-900" />
                            <x-text-input id="title" name="title" class="mt-1 block w-full bg-slate-50 border-gray-300 focus:border-brand-pink focus:ring-brand-pink" value="{{ old('title', $article->title) }}" required />
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <x-input-label for="content" value="Konten Artikel" class="font-semibold text-gray-900" />
                            <textarea name="content" id="content" rows="12" class="mt-1 block w-full bg-slate-50 border-gray-300 rounded-lg shadow-sm focus:border-brand-pink focus:ring-brand-pink" required>{{ old('content', $article->content) }}</textarea>
                            @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <x-input-label for="image" value="Ganti Gambar (Opsional)" class="font-semibold text-gray-900" />
                            <div class="mt-2 mb-2">
                                <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="h-40 w-auto rounded-lg shadow-md">
                            </div>
                            <input type="file" name="image" id="image" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-pink-50 file:text-brand-pink hover:file:bg-pink-100">
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti gambar.</p>
                            @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-4 bg-gray-50 px-8 py-4 border-t border-gray-200">
                        <a href="{{ route('admin.articles.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Batal</a>
                        <button type="submit" class="px-6 py-2 brand-gradient text-white font-semibold rounded-full hover:opacity-90 transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>