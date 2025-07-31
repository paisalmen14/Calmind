<x-app-layout>
    <section class="py-2 md:py-2 lg:py-2 px-4 md:px-8 lg:px-12">
        <div class="max-w-full mx-auto">
            <div class="min-h-[400px] flex items-center justify-center relative overflow-hidden bg-no-repeat 
                        p-10 md:p-16 lg:p-20 rounded-2xl card-shadow"
                style="background-image: url('/images/journaling.jpg'); background-size: 120%; background-position: 50% 50%; background-color: #5EABD6;">
                
                <div class="max-w-6xl text-center text-white">
                    <h2 class="font-londrina text-6xl lg:text-7xl leading-tight mb-4 text-outline-effect-md">
                        Self-Journaling
                    </h2>
                    <p class="font-quicksand text-lg mb-8 font-bold text-outline-effect-sm">
                        Tulis segala hal yang terjadi hari ini. Abadikan seluruh momenmu melalui tulisan. Apapun yang kamu rasakan, apapun yang terjadi hari ini, percayalah bahwa semua akan baik-baik saja.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-full mx-auto sm:px-6 lg:px-8 mt-4">
        <div class="flex justify-end items-center mb-6">
            <a href="{{ route('daily-diary.create') }}" class="inline-block px-4 py-2 bg-[#FFB4B4] text-black font-gabarito rounded-full shadow-sm hover:bg-[#E14434] transition-all">
                + Tulis Jurnal Harian
            </a>
        </div>

        <div class="h-px bg-black my-8"></div>

        <div class="mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-400 text-green-800 p-4 rounded-md">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('info'))
                <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 text-blue-800 p-4 rounded-md">
                    <p>{{ session('info') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-400 text-red-800 p-4 rounded-md">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="space-y-6">
                @forelse ($diaries as $diary)
                    <div class="grid grid-cols-[80px_1fr] md:grid-cols-[100px_1fr] gap-4 items-start">
                        {{-- KOLOM KIRI: TANGGAL --}}
                        <div class="font-quicksand text-center md:text-left flex flex-col justify-center items-center md:items-start pt-2">
                            {{-- Tampilkan Hari --}}
                            <span class="text-2xl md:text-5xl font-bold text-black leading-none">{{ $diary->entry_date->format('d') }}</span> 
                            {{-- Tampilkan Bulan --}}
                            <span class="text-xl md:text-4xl text-black">{{ $diary->entry_date->isoFormat('MMM') }}</span> 
                        </div>

                        {{-- KOLOM KANAN: KARTU DIARY --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <h3 class="font-gabarito text-3xl text-black">{{ $diary->title }}</h3>
                                </div>

                                <div class="font-gabarito flex space-x-2 flex-shrink-0">
                                    <a href="{{ route('daily-diary.edit', $diary) }}" class="text-md text-[#E14434] hover:underline">Edit</a>
                                    <form action="{{ route('daily-diary.destroy', $diary) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus entri diary ini?');" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-md text-[#E14434] hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </div>
                            <p class="mt-2 text-black line-clamp-3">{{ $diary->content }}</p>
                            <div class="h-px bg-black my-3"></div>
                            <a href="{{ route('daily-diary.show', $diary) }}" class="mt-1 inline-block text-md font-gabarito text-black hover:text-[#E14434]">Lihat lebih detail &rarr;</a>
                        </div>
                    </div>

                    @if (!$loop->last)
                        <div class="h-px bg-black my-6"></div>
                    @endif

                @empty
                    {{-- ... empty state ... --}}
                    <div class="bg-white p-12 rounded-2xl card-shadow border border-gray-100 text-center">
                        <p class="text-gray-500">Anda belum menulis diary. Mulailah hari ini!</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-8">
                {{ $diaries->links() }}
            </div>
        </div>
    </div>
</x-app-layout>