<x-app-layout>    
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('daily-diary.index') }}" class="inline-flex items-center px-4 py-2 bg-[#FFB4B4] text-black font-gabarito text-sm
                            rounded-full shadow-sm hover:bg-[#E14434] transition-colors whitespace-nowrap">
                    &larr; Kembali ke Daftar Diary
                </a>
            </div>

            <div class="bg-[#5EABD6] p-8 rounded-2xl shadow-lg">
                <h1 class="font-londrina text-5xl text-black mb-3">{{ $dailyDiary->title }}</h1> {{-- Tampilkan Judul --}}
                <p class="text-sm text-black mb-6">{{ $dailyDiary->entry_date->isoFormat('dddd, D MMMM YYYY') }} &bull; Ditulis {{ $dailyDiary->created_at->diffForHumans() }}</p>

                <div class="prose max-w-none text-lg text-black leading-relaxed">
                    {!! nl2br(e($dailyDiary->content)) !!}
                </div>

                {{-- Bagian Rangkuman Entri telah dihapus di sini --}}

                <div class="mt-8 border-t border-black pt-6 text-right">
                    <a href="{{ route('daily-diary.edit', $dailyDiary) }}" class="inline-block px-4 py-2 text-sm font-gabarito text-black hover:text-[#E14434] transition-all">
                        Edit Diary
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>