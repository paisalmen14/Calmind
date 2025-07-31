<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @auth
            <div class="mb-8">
                <x-mood-stats :moodHistories="$moodHistories" :moodStats="$moodStats" :averageMood="$averageMood" />
            </div>
            @endauth
        </div>

        <section class="py-12 md:py-16 lg:py-20 px-4 md:px-8 lg:px-12">
            <div class="max-w-full mx-auto">
                <div class="min-h-[400px] flex items-end relative overflow-hidden bg-no-repeat 
                            p-10 md:p-16 lg:p-20 rounded-2xl card-shadow"
                     style="background-image: url('/images/beranda1.jpg'); background-size: 120%; background-position: 50% 87%; background-color: #5EABD6;">
                    
                    <div class="max-w-6xl text-left text-white">
                        <h2 class="font-londrina text-6xl lg:text-7xl leading-tight mb-4 text-outline-effect-md">
                            Pahami Dirimu, <br> Jaga Kesehatan Mentalmu
                        </h2>
                        <p class="font-quicksand text-lg mb-8 font-bold text-outline-effect-sm">
                            Dapatkan kekuatan dan perspektif baru dari setiap bacaan. Temukan inspirasi, strategi, dan panduan yang Anda butuhkan untuk memahami diri dan tumbuh menjadi pribadi yang lebih baik.
                        </p>
                        <a href="#" class="font-gabarito px-5 py-2.5 bg-[#FFB4B4] text-black rounded-full hover:bg-[#E14434] transition-opacity whitespace-nowrap">
                            Daily Mood
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($articles as $article)
                {{-- ... Kartu Artikel ... --}}
                <div class="overflow-hidden rounded-2xl shadow-sm flex flex-col group">
                    <a href="{{ route('articles.show', $article) }}">
                        <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300">
                    </a>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="font-bold font-gabarito text-xl text-black mb-2 leading-tight">
                            <a href="{{ route('articles.show', $article) }}" class="hover:text-brand-pink transition-colors">
                                {{ Str::limit($article->title, 60) }}
                                
                        </h3>
                        <p class="text-black text-sm line-clamp-3 flex-grow">
                            {{ Str::limit(strip_tags($article->content), 150) }}
                        </p>
                        <div class="mt-4 font-gabarito">
                            <a href="{{ route('articles.show', $article) }}" class="font-semibold text-black hover:underline">
                            </a>
                        </div>
                        <div class="text-xs text-black mt-2">
                            Diposting {{ $article->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-[#5EABD6] p-12 rounded-2xl card-shadow border border-gray-100 text-center">
                    <svg class="mx-auto h-16 w-16 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                    </svg>
                    <h3 class="mt-4 text-xl font-gabarito text-black">Belum Ada Artikel</h3>
                    <p class="mt-2 text-base text-black">Saat ini belum ada artikel yang dipublikasikan. Silakan cek kembali nanti.</p>
                </div>
                @endforelse
            </div>
            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>