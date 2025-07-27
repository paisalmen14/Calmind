<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Edit Diary') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <form action="{{ route('daily-diary.update', $dailyDiary) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-8">
                        {{-- Kolom untuk Judul Diary --}}
                        <div class="mb-6">
                            <label for="title" class="block font-semibold text-base text-gray-900">Judul Diary Anda</label>
                            <p class="text-sm text-gray-600 mb-2">Berikan judul singkat untuk entri diary ini.</p>
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full bg-slate-50 border-gray-300 rounded-lg shadow-sm focus:border-brand-pink focus:ring-brand-pink" :value="old('title', $dailyDiary->title)" required autofocus />
                            @error('title')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kolom untuk Konten Diary --}}
                        <div>
                            <label for="content" class="block font-semibold text-base text-gray-900">Bagaimana perasaan Anda pada tanggal {{ $dailyDiary->entry_date->isoFormat('dddd, D MMMM YYYY') }}?</label>
                            <p class="text-sm text-gray-600 mb-4">Anda dapat mengubah entri diary ini.</p>
                            
                            <textarea name="content" id="content" rows="10" 
                                      class="mt-1 block w-full bg-slate-50 border-gray-300 rounded-lg shadow-sm focus:border-brand-pink focus:ring-brand-pink">{{ old('content', $dailyDiary->content) }}</textarea>
                            @error('content')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 bg-gray-50 px-8 py-4 border-t border-gray-200">
                        <a href="{{ route('daily-diary.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Batal</a>
                        <button type="submit" class="px-6 py-2 brand-gradient text-white font-semibold rounded-full hover:opacity-90 transition-all transform hover:scale-105">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>