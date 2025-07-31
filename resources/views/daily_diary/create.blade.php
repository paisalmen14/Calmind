<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('daily-diary.index') }}" 
                class="inline-flex items-center px-4 py-2 bg-[#FFB4B4] text-black font-gabarito text-sm
                        rounded-full shadow-sm hover:bg-[#E14434] transition-colors whitespace-nowrap">
                    &larr; Kembali ke Self-Journaling
                </a>
            </div>

            <div class="bg-[#5EABD6] overflow-hidden shadow-xl sm:rounded-2xl">
                <form action="{{ route('daily-diary.store') }}" method="POST">
                    @csrf
                    <div class="p-8">
                        {{-- Kolom untuk Judul Diary --}}
                        <div class="mb-6">
                            <label for="title" class="block font-gabarito text-2xl text-black">Judul</label>
                            <p class="text-sm text-black mb-2">Berikan judul untuk jurnal hari ini.</p>
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full bg-slate-50 border-gray-300 rounded-lg shadow-sm focus:border-black focus:ring-black" :value="old('title')" required autofocus />
                            @error('title')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kolom untuk Konten Diary --}}
                        <div>
                            <label for="content" class="block font-gabarito text-2xl text-black">Ada cerita apa hari ini?</label>
                            <p class="text-sm text-black mb-4">Disinilah tempat untukmu menceritakan semua hal yang terjadi hari ini.</p>
                            
                            <textarea name="content" id="content" rows="10" 
                                      class="mt-1 block w-full bg-slate-50 border-gray-300 rounded-lg shadow-sm focus:border-black focus:ring-black">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 px-8 py-4">
                        <a href="{{ route('daily-diary.index') }}" class="text-sm font-medium text-black hover:text-[#E14434]">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-[#FFB4B4] text-black font-gabarito text-sm rounded-full hover:bg-[#E14434]">
                            Simpan Diary
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>