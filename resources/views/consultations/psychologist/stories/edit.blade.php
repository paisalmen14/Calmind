<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Cerita') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    
                    {{-- Pesan pengingat tentang batas waktu edit --}}
                    <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800">
                        <p class="font-bold">Perhatian</p>
                        <p class="text-sm">Anda hanya dapat mengedit cerita ini dalam **10 menit** setelah dipublikasikan.</p>
                    </div>

                    <form action="{{ route('stories.update', $story) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Textarea untuk konten cerita --}}
                        <div>
                            <label for="content" class="block font-medium text-sm text-gray-700 mb-1">Cerita Anda</label>
                            <textarea name="content" id="content" rows="10" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('content', $story->content) }}</textarea>
                            @error('content')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Checkbox untuk mode anonim --}}
                        <div class="mt-6">
                            <label for="is_anonymous" class="inline-flex items-center">
                                <input type="checkbox" name="is_anonymous" id="is_anonymous" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_anonymous', $story->is_anonymous) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-600">Publikasikan sebagai Anonim</span>
                            </label>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end mt-8 border-t pt-6">
                            <a href="{{ url()->previous() }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>