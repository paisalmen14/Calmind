<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-serif font-bold text-3xl text-gray-900">
                {{ __('Rangkuman Diary Mingguan') }}
            </h2>
            <a href="{{ route('daily-diary.index') }}" class="text-sm font-medium text-gray-600 hover:text-brand-pink">
                &larr; Kembali ke Diary Harian
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                <h3 class="font-serif text-2xl font-bold text-gray-900 mb-4">
                    Rangkuman Minggu: {{ $startDate->isoFormat('D MMMM') }} - {{ $endDate->isoFormat('D MMMM YYYY') }}
                </h3>

                @if ($weeklyDiaries->isNotEmpty())
                    <div class="prose max-w-none text-lg text-gray-800 leading-relaxed">
                        <p>Berikut adalah rangkuman dari entri diary Anda selama minggu ini:</p>
                        <hr>
                        <p class="whitespace-pre-wrap">{{ $summaryContent }}</p>
                        <hr>
                        <p class="mt-4 text-sm text-gray-600">
                            *Catatan: Rangkuman ini dibuat berdasarkan entri-entri Anda. Untuk analisis lebih mendalam, pertimbangkan untuk berkonsultasi dengan psikolog.
                        </p>
                    </div>
                @else
                    <div class="text-center py-10 px-6 border-2 border-dashed rounded-xl">
                        <p class="text-gray-500">Tidak ada entri diary untuk minggu ini. Silakan tulis diary Anda!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>