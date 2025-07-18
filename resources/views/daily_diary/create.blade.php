<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Tulis Diary Hari Ini') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <form action="{{ route('daily-diary.store') }}" method="POST">
                    @csrf
                    <div class="p-8">
                        <div>
                            <label for="content" class="block font-semibold text-base text-gray-900">Bagaimana perasaan Anda hari ini?</label>
                            <p class="text-sm text-gray-600 mb-4">Tuangkan pikiran dan perasaan Anda di sini.</p>
                            
                            <textarea name="content" id="content" rows="10" 
                                      class="mt-1 block w-full bg-slate-50 border-gray-300 rounded-lg shadow-sm focus:border-brand-pink focus:ring-brand-pink">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 bg-gray-50 px-8 py-4 border-t border-gray-200">
                        <a href="{{ route('daily-diary.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Batal</a>
                        <button type="submit" class="px-6 py-2 brand-gradient text-white font-semibold rounded-full hover:opacity-90 transition-all transform hover:scale-105">
                            Simpan Diary
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>