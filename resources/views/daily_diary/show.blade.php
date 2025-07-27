<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif font-bold text-3xl text-gray-900">
                {{ __('Detail Diary') }}
            </h2>
            <a href="{{ route('daily-diary.index') }}" class="text-sm font-medium text-gray-600 hover:text-brand-pink">
                &larr; Kembali ke Daftar Diary
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                <h1 class="font-serif text-3xl font-bold text-gray-900 mb-3">{{ $dailyDiary->title }}</h1> {{-- Tampilkan Judul --}}
                <p class="text-sm text-gray-500 mb-6">{{ $dailyDiary->entry_date->isoFormat('dddd, D MMMM YYYY') }} &bull; Ditulis {{ $dailyDiary->created_at->diffForHumans() }}</p>

                <div class="prose max-w-none text-lg text-gray-800 leading-relaxed">
                    {!! nl2br(e($dailyDiary->content)) !!}
                </div>

                {{-- Bagian Rangkuman Entri telah dihapus di sini --}}

                <div class="mt-8 border-t border-gray-200 pt-6 text-right">
                    <a href="{{ route('daily-diary.edit', $dailyDiary) }}" class="inline-block px-4 py-2 bg-brand-pink text-white font-semibold rounded-full hover:opacity-90 transition-all">
                        Edit Diary
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>