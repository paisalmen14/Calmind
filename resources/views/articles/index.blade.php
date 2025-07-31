<x-app-layout>
    {{--
    =====================================================================
    LANGKAH 1: Tambahkan Alpine.js untuk mengontrol modal 
    =====================================================================
    x-data="{ dailyMoodModalOpen: false }" akan membuat state untuk modal.
    @keydown.escape.window="dailyMoodModalOpen = false" agar modal bisa ditutup dengan tombol Escape.
    --}}
    <div class.py-12" x-data="{ dailyMoodModalOpen: false }" @keydown.escape.window="dailyMoodModalOpen = false">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Bagian Mood Tracker dan Statistik sekarang dipindahkan ke dalam modal di bawah --}}
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

                        {{--
                        =====================================================================
                        LANGKAH 2: Ubah tombol "Daily Mood" menjadi pemicu modal
                        =====================================================================
                        href="#" dihapus dan diganti dengan @click.prevent="dailyMoodModalOpen = true"
                        untuk membuka modal tanpa me-refresh halaman.
                        --}}
                        <button @click.prevent="dailyMoodModalOpen = true" class="font-gabarito px-5 py-2.5 bg-[#FFB4B4] text-black rounded-full hover:bg-[#E14434] transition-opacity whitespace-nowrap">
                            Daily Mood
                        </button>
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
                            </a>
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

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        </div>

        {{--
        =====================================================================
        LANGKAH 3: Pindahkan Mood Tracker & Statistik ke dalam Modal
        =====================================================================
        Kode Mood Tracker dan Statistik kini berada di dalam struktur modal.
        - x-show="dailyMoodModalOpen" akan menampilkan modal jika nilainya true.
        - @click.away="dailyMoodModalOpen = false" akan menutup modal jika mengklik di luar area modal.
        - Ada tombol close (X) yang juga berfungsi untuk menutup modal.
        --}}
        @auth
        <div
            x-show="dailyMoodModalOpen"
            style="display: none;"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">

            <div @click.away="dailyMoodModalOpen = false" class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto p-6 md:p-8">
                {{-- Tombol Close Modal --}}
                <button @click="dailyMoodModalOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- KONTEN MOOD TRACKER & STATISTIK YANG DIPINDAHKAN --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg mb-8">
                    <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                        <div class="flex flex-col items-center">
                            <div class="relative w-full max-w-lg bg-gray-900 rounded-lg shadow-md border-2 border-gray-700">
                                <video id="video-preview" width="640" height="480" autoplay muted class="rounded-lg" style="transform: scaleX(-1);"></video>
                                <img id="captured-image" width="640" height="480" class="rounded-lg hidden" alt="Hasil Tangkapan Wajah">
                                <div id="loading-spinner" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-lg hidden">
                                    <svg class="animate-spin h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="ml-4 text-white text-lg">Menganalisis...</span>
                                </div>
                            </div>
                            <div class="mt-6 flex space-x-4">
                                <button id="start-camera-btn" class="flex items-center px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 disabled:opacity-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 001.553.832l3-2a1 1 0 000-1.664l-3-2z" />
                                    </svg>
                                    Nyalakan Kamera
                                </button>
                                <button id="capture-btn" class="flex items-center px-6 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75 disabled:opacity-50 transition-colors hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-7.536 5.879a1 1 0 001.415 1.414 3.498 3.498 0 004.242 0 1 1 0 001.415-1.414 5.498 5.498 0 00-7.072 0z" clip-rule="evenodd" />
                                    </svg>
                                    Cek Mood Saya
                                </button>
                                <button id="retake-btn" class="flex items-center px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75 transition-colors hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 110 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                    </svg>
                                    Ambil Ulang
                                </button>
                            </div>
                        </div>

                        <div id="analysis-section" class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 hidden">
                            <h3 class="text-2xl font-bold text-center mb-4">Hasil Analisis Mood Anda</h3>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                                <p class="text-center text-xl mb-4">Anda terdeteksi sedang merasakan: <span id="result-emotion" class="font-bold text-blue-500 dark:text-blue-400"></span></p>
                                <div class="prose prose-lg dark:prose-invert max-w-none">
                                    <div id="result-advice"></div>
                                    <h4 class="mt-4 font-semibold">Saran Kegiatan:</h4>
                                    <ul id="result-activities" class="list-disc pl-5"></ul>
                                </div>
                            </div>
                        </div>

                        <canvas id="canvas" style="display: none;"></canvas>
                    </div>
                </div>

                <div class="mb-8">
                    <x-mood-stats :moodHistories="$moodHistories" :moodStats="$moodStats" :averageMood="$averageMood" />
                </div>
            </div>
        </div>
        @endauth
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // === Data Saran & Aktivitas (Bisa dipindahkan ke database jika perlu) ===
            const moodAnalysisData = {
                'Senang': {
                    advice: 'Anda terlihat bahagia! Pertahankan energi positif ini dan bagikan kebahagiaan Anda kepada orang lain. Senyum Anda menular!',
                    activities: ['Dengarkan musik yang ceria.', 'Lakukan aktivitas di luar ruangan.', 'Hubungi teman atau keluarga.']
                },
                'Sedih': {
                    advice: 'Tidak apa-apa untuk merasa sedih. Beri diri Anda waktu untuk merasakan emosi ini. Ingat, perasaan ini akan berlalu.',
                    activities: ['Tulis jurnal tentang perasaan Anda.', 'Tonton film komedi.', 'Bicara dengan seseorang yang Anda percaya.']
                },
                'Marah': {
                    advice: 'Ambil napas dalam-dalam. Cobalah untuk mengidentifikasi apa yang memicu kemarahan Anda dan temukan cara yang sehat untuk menyalurkannya.',
                    activities: ['Lakukan olahraga ringan seperti berjalan kaki.', 'Dengarkan musik yang menenangkan.', 'Lakukan teknik relaksasi pernapasan.']
                },
                'Terkejut': {
                    advice: 'Sesuatu yang tak terduga terjadi? Luangkan waktu sejenak untuk memprosesnya. Perasaan ini biasanya tidak berlangsung lama.',
                    activities: ['Ceritakan apa yang terjadi pada teman.', 'Tulis tentang pengalaman itu.', 'Lakukan sesuatu yang rutin untuk merasa lebih stabil.']
                },
                'Takut': {
                    advice: 'Rasa takut adalah respons alami. Kenali apa yang membuat Anda takut dan hadapi dengan perlahan. Anda lebih kuat dari yang Anda kira.',
                    activities: ['Fokus pada pernapasan Anda.', 'Lakukan meditasi singkat.', 'Bicaralah pada diri sendiri dengan kalimat positif.']
                },
                'Jijik': {
                    advice: 'Perasaan ini menandakan ketidaksukaan yang kuat. Cobalah untuk menjauh dari sumber perasaan ini jika memungkinkan.',
                    activities: ['Alihkan perhatian Anda ke sesuatu yang netral atau menyenangkan.', 'Bersihkan atau rapikan lingkungan sekitar Anda.', 'Dengarkan podcast atau audiobook.']
                },
                'Netral': {
                    advice: 'Anda berada dalam kondisi yang tenang dan seimbang. Ini adalah waktu yang baik untuk fokus pada tugas atau melakukan refleksi diri.',
                    activities: ['Buat daftar tugas untuk hari ini.', 'Baca buku atau artikel menarik.', 'Rencanakan sesuatu yang Anda nantikan.']
                }
            };

            // === Seleksi Elemen DOM ===
            const video = document.getElementById('video-preview');
            const capturedImage = document.getElementById('captured-image');
            const canvas = document.getElementById('canvas');
            const startCameraBtn = document.getElementById('start-camera-btn');
            const captureBtn = document.getElementById('capture-btn');
            const retakeBtn = document.getElementById('retake-btn');
            const loadingSpinner = document.getElementById('loading-spinner');
            const analysisSection = document.getElementById('analysis-section');
            const resultEmotion = document.getElementById('result-emotion');
            const resultAdvice = document.getElementById('result-advice');
            const resultActivities = document.getElementById('result-activities');

            let stream = null; // Variabel untuk menyimpan stream video

            // === Event Listeners ===
            startCameraBtn.addEventListener('click', startCamera);
            captureBtn.addEventListener('click', captureAndAnalyze);
            retakeBtn.addEventListener('click', retakePicture);

            // === Fungsi-fungsi ===

            // 1. Fungsi untuk Menyalakan Kamera
            async function startCamera() {
                analysisSection.classList.add('hidden');

                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: true,
                        audio: false
                    });
                    video.srcObject = stream;
                    video.classList.remove('hidden');
                    capturedImage.classList.add('hidden');

                    startCameraBtn.classList.add('hidden');
                    captureBtn.classList.remove('hidden');
                    retakeBtn.classList.add('hidden');

                } catch (err) {
                    console.error("Error accessing camera: ", err);
                    alert('Tidak dapat mengakses kamera. Pastikan Anda memberikan izin pada browser.');
                }
            }

            // 2. Fungsi untuk Mengambil Gambar dan Menganalisis
            async function captureAndAnalyze() {
                loadingSpinner.classList.remove('hidden');
                captureBtn.disabled = true;

                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                context.translate(canvas.width, 0);
                context.scale(-1, 1);
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                context.setTransform(1, 0, 0, 1, 0, 0);

                const dataUrl = canvas.toDataURL('image/jpeg');
                capturedImage.src = dataUrl;
                capturedImage.classList.remove('hidden');
                stopCamera();

                try {
                    const response = await fetch("{{ route('emotion.detect') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            image: dataUrl
                        })
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.error || 'Terjadi kesalahan yang tidak diketahui.');
                    }

                    displayAnalysis(result.emotion);

                } catch (error) {
                    console.error('Error during analysis:', error);
                    alert(error.message || 'Terjadi kesalahan saat menganalisis mood. Silakan coba lagi.');
                    retakePicture();
                } finally {
                    loadingSpinner.classList.add('hidden');
                    captureBtn.classList.add('hidden');
                    retakeBtn.classList.remove('hidden');
                    captureBtn.disabled = false;
                }
            }

            // 3. Fungsi untuk Menghentikan Kamera
            function stopCamera() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                video.classList.add('hidden');
            }

            // 4. Fungsi untuk Mengambil Ulang Gambar
            function retakePicture() {
                analysisSection.classList.add('hidden');
                capturedImage.classList.add('hidden');
                startCamera();
            }

            // 5. Fungsi untuk Menampilkan Hasil Analisis
            function displayAnalysis(emotion) {
                const data = moodAnalysisData[emotion] || moodAnalysisData['Netral'];

                resultEmotion.textContent = emotion;
                resultAdvice.innerHTML = `<p>${data.advice}</p>`;
                resultActivities.innerHTML = data.activities.map(act => `<li>${act}</li>`).join('');

                analysisSection.classList.remove('hidden');

                setTimeout(() => {
                    window.location.reload();
                }, 15000);
            }
        });
    </script>
    @endpush
</x-app-layout>