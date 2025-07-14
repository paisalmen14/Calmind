<x-app-layout>
    <x-slot name="header">
        {{-- Menggunakan font serif untuk judul agar konsisten --}}
        <h2 class="font-serif text-3xl font-bold text-gray-900">
            {{ __('Tulis Cerita Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Menggunakan card putih dengan shadow lembut --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <form action="{{ route('stories.store') }}" method="POST">
                    @csrf
                    <div class="p-8">
                        <div>
                            {{-- Label dipertegas dan diubah warnanya --}}
                            <label for="content" class="block font-semibold text-base text-gray-900">Apa yang sedang kamu rasakan?</label>
                            <p class="text-sm text-gray-600 mb-4">Tuangkan isi hatimu di sini. Kamu bisa memilih untuk mengirimnya secara anonim.</p>
                            
                            {{-- Textarea disesuaikan dengan tema --}}
                            <textarea name="content" id="content" rows="10" 
                                      class="mt-1 block w-full bg-slate-50 border-gray-300 rounded-lg shadow-sm focus:border-brand-pink focus:ring-brand-pink">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label for="is_anonymous" class="inline-flex items-center">
                                {{-- Checkbox disesuaikan dengan warna tema --}}
                                <input type="checkbox" name="is_anonymous" id="is_anonymous" class="rounded border-gray-300 text-brand-pink shadow-sm focus:ring-brand-pink">
                                <span class="ms-2 text-sm text-gray-700">{{ __('Publikasikan sebagai Anonim') }}</span>
                            </label>
                        </div>
                    </div>

                    {{-- Tombol aksi dengan style baru --}}
                    <div class="flex items-center justify-end gap-4 bg-gray-50 px-8 py-4 border-t border-gray-200">
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Batal</a>
                        <button type="submit" class="px-6 py-2 brand-gradient text-white font-semibold rounded-full hover:opacity-90 transition-all transform hover:scale-105">
                            Publikasikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>