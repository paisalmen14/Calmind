<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif font-bold text-3xl text-gray-900">
                {{ __('Tulis Cerita Baru') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-brand-purple">
                &larr; Kembali ke Ruang Cerita
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <form action="{{ route('stories.store') }}" method="POST">
                    @csrf
                    <div class="p-8">
                        <div>
                            <label for="content" class="block font-semibold text-base text-gray-900">Apa yang sedang kamu rasakan?</label>
                            <p class="text-sm text-gray-600 mb-4">Tuangkan isi hatimu di sini. Kamu bisa memilih untuk mengirimnya secara anonim.</p>
                            
                            <textarea name="content" id="content" rows="12" 
                                      class="mt-1 block w-full bg-slate-50 border-gray-300 rounded-lg shadow-sm focus:border-brand-purple focus:ring-brand-purple" 
                                      placeholder="Tulis ceritamu di sini...">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label for="is_anonymous" class="inline-flex items-center">
                                <input type="checkbox" name="is_anonymous" id="is_anonymous" class="rounded border-gray-300 text-brand-purple shadow-sm focus:ring-brand-purple">
                                <span class="ms-2 text-sm text-gray-700">{{ __('Publikasikan sebagai Anonim') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 bg-gray-50 px-8 py-4 border-t border-gray-200">
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Batal</a>
                        <button type="submit" class="px-6 py-2 brand-gradient text-white font-semibold rounded-full hover:opacity-90 transition-all transform hover:scale-105">
                            Publikasikan Cerita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
