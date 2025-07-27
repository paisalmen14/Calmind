<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('dashboard') }}" 
                class="inline-flex items-center px-4 py-2 bg-[#FFB4B4] text-black font-gabarito text-sm
                        rounded-full shadow-sm hover:bg-[#E14434] transition-colors whitespace-nowrap">
                    &larr; Kembali ke Ruang Cerita
                </a>
            </div>

            <div class="bg-[#5EABD6] overflow-hidden shadow-xl sm:rounded-2xl">
                <form action="{{ route('stories.store') }}" method="POST">
                    @csrf
                    <div class="p-8">
                        <div>
                            <label for="content" class="block font-gabarito text-2xl text-black">Ruang Cerita</label>
                            <p class="text-sm text-black mb-4">Ceritakan semua hal yang dapat diceritakan.</p>
                            
                            <textarea name="content" id="content" rows="12" 
                                      class="mt-1 block w-full bg-slate-50 border-gray-300 rounded-lg shadow-sm focus:border-brand-purple focus:ring-brand-purple" 
                                      placeholder="Tulis ceritamu di sini...">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 flex justify-between items-center flex-nowrap">
                            <label for="is_anonymous" class="inline-flex items-center">
                                <input type="checkbox" name="is_anonymous" id="is_anonymous" class="rounded border-gray-300 text-black shadow-sm focus:ring-black">
                                <span class="ms-2 font-gabarito text-sm text-black">{{ __('Publikasikan sebagai Anonim') }}</span>
                            </label>

                            <div class="flex items-center gap-4">
                                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-black hover:text-[#E14434]">Batal</a>
                                <button type="submit" class="px-6 py-2 bg-[#FFB4B4] text-black font-gabarito text-sm rounded-full hover:bg-[#E14434]">
                                    Publikasikan Cerita
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
