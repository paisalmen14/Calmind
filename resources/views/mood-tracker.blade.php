<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Mood Tracker') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
        <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

          {{-- BAGIAN VIDEO, GAMBAR HASIL TANGKAPAN & TOMBOL --}}
          <div class="flex flex-col items-center">
            <div class="relative w-full max-w-lg bg-gray-900 rounded-lg shadow-md border-2 border-gray-700">
              <video id="video-preview" width="640" height="480" autoplay muted class="rounded-lg"></video>
              <img id="captured-image" width="640" height="480" class="rounded-lg hidden" alt="Hasil Tangkapan Wajah">
              <div id="loading-spinner" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-lg hidden">
                <svg class="animate-spin h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
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

          {{-- BAGIAN HASIL & ANALISIS --}}
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
    </div>
  </div>

  @push('scripts')
  <script>
    // KAMUS UNTUK ANALISIS MOOD (KONTEN LEBIH LENGKAP)
    const moodAnalysisData = {
      'senang': {
        emoji: '😊',
        advice: '<p>Luar biasa! Merasa senang adalah anugerah. Manfaatkan energi positif ini untuk menjadi produktif atau menyebarkan kebaikan. Ingatlah momen ini sebagai pengingat bahwa hari-hari baik itu ada dan akan datang lagi.</p>',
        activities: ['Tulis tiga hal yang membuat Anda bersyukur hari ini di diary.', 'Lakukan kebaikan kecil untuk orang lain, seperti memberi pujian.', 'Salurkan energi kreatif Anda: menggambar, menari, atau bernyanyi.']
      },
      'sedih': {
        emoji: '😢',
        advice: '<p>Sangat wajar untuk merasa sedih, dan perasaan Anda valid. Jangan melawannya, biarkan ia mengalir. Kesedihan adalah sinyal bahwa ada sesuatu yang penting bagi Anda. Beri diri Anda waktu dan ruang untuk pulih.</p>',
        activities: ['Lakukan aktivitas yang menenangkan seperti mandi air hangat atau minum teh herbal.', 'Tonton film atau dengarkan lagu yang bisa membuat Anda merasa lebih baik.', 'Hubungi teman dekat atau anggota keluarga untuk sekadar berbagi cerita.']
      },
      'marah': {
        emoji: '😠',
        advice: '<p>Kemarahan adalah emosi yang kuat. Daripada menahannya, cari cara sehat untuk melepaskannya. Coba identifikasi apa yang ada di balik kemarahan Anda: apakah itu rasa frustrasi, ketidakadilan, atau sakit hati? Memahaminya adalah langkah pertama untuk mengatasinya.</p>',
        activities: ['Lakukan aktivitas fisik seperti lari cepat, workout, atau memukul bantal untuk melepaskan ketegangan.', 'Tulis semua yang membuat Anda marah di secarik kertas, lalu robek kertas itu.', 'Praktikkan teknik pernapasan 4-7-8: tarik napas 4 detik, tahan 7 detik, hembuskan 8 detik.']
      },
      'netral': {
        emoji: '😐',
        advice: '<p>Keadaan netral adalah fondasi yang kokoh. Ini adalah momen yang sempurna untuk melakukan refleksi diri atau merencanakan sesuatu tanpa gangguan emosi yang kuat. Manfaatkan kejernihan pikiran ini.</p>',
        activities: ['Buat daftar tugas (to-do list) untuk esok hari agar lebih terorganisir.', 'Pelajari skill atau baca topik baru yang menarik minat Anda.', 'Lakukan meditasi body scan untuk lebih terhubung dengan kondisi tubuh Anda.']
      },
      'terkejut': {
        emoji: '😮',
        advice: '<p>Rasa terkejut membuat sistem saraf kita aktif. Beri diri Anda waktu untuk memproses informasi atau kejadian tak terduga ini. Setelah detak jantung kembali normal, Anda bisa memutuskan bagaimana harus merespons.</p>',
        activities: ['Duduk atau berdiri diam, rasakan pijakan kaki Anda di lantai untuk grounding.', 'Jelaskan kejadian tersebut kepada diri sendiri atau orang lain untuk membuatnya lebih masuk akal.', 'Alihkan fokus sejenak ke aktivitas rutin yang sederhana, seperti menyeduh teh.']
      },
      'takut': {
        emoji: '😨',
        advice: '<p>Rasa takut atau cemas adalah respons alami terhadap ancaman. Ingatkan diri Anda bahwa perasaan ini akan berlalu. Fokus pada apa yang bisa Anda kontrol saat ini, sekecil apa pun itu. Anda lebih kuat dari yang Anda kira.</p>',
        activities: ['Terapkan teknik grounding 5-4-3-2-1: sebutkan 5 benda yang Anda lihat, 4 benda yang Anda sentuh, 3 suara yang Anda dengar, 2 bau yang Anda cium, dan 1 rasa yang bisa Anda kecap.', 'Genggam es batu atau basuh wajah dengan air dingin untuk "mengejutkan" sistem saraf Anda agar kembali tenang.', 'Dengarkan musik instrumental yang menenangkan atau suara alam.']
      },
      'jijik': {
        emoji: '🤢',
        advice: '<p>Jijik adalah emosi yang melindungi kita dari hal-hal yang mungkin berbahaya atau tidak sejalan dengan nilai-nilai kita. Hormati perasaan ini. Ini bisa menjadi sinyal untuk menetapkan batasan atau mengubah lingkungan Anda.</p>',
        activities: ['Ciptakan lingkungan yang menyenangkan secara sensorik: nyalakan lilin aroma terapi atau putar musik yang Anda suka.', 'Lakukan aktivitas bersih-bersih atau merapikan sesuatu untuk mendapatkan kembali rasa kontrol.', 'Fokus pada hobi atau aktivitas yang Anda nikmati untuk menggantikan perasaan negatif.']
      }
    };

    // Mengambil elemen dari HTML
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
    const detectUrl = "{{ route('emotion.detect') }}";

    startCameraBtn.addEventListener('click', async () => {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({
          video: true
        });
        video.srcObject = stream;
        startCameraBtn.classList.add('hidden');
        captureBtn.classList.remove('hidden');
        captureBtn.disabled = false;
      } catch (err) {
        console.error("Kamera tidak bisa diakses:", err);
        alert('Tidak bisa mengakses kamera. Pastikan Anda memberikan izin pada browser.');
      }
    });

    captureBtn.addEventListener('click', () => {
      loadingSpinner.classList.remove('hidden');
      captureBtn.disabled = true;

      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext('2d').drawImage(video, 0, 0, video.videoWidth, video.videoHeight);

      const imageUrl = canvas.toDataURL('image/jpeg');
      capturedImage.src = imageUrl;
      capturedImage.classList.remove('hidden');
      video.classList.add('hidden');

      captureBtn.classList.add('hidden');
      retakeBtn.classList.remove('hidden');

      canvas.toBlob((blob) => {
        const formData = new FormData();
        formData.append('image', blob, 'capture.jpg');

        // ===================================================
        // --- AWAL BLOK PERBAIKAN PENANGANAN ERROR ---
        // ===================================================
        fetch(detectUrl, {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          })
          .then(response => {
            // Jika respons TIDAK ok, kita proses body-nya untuk mendapatkan pesan error
            if (!response.ok) {
              // Kembalikan promise yang berisi data error JSON
              return response.json().then(errorData => {
                // Lempar error baru dengan pesan dari server agar bisa ditangkap .catch()
                throw new Error(errorData.message || 'Gagal memproses permintaan.');
              });
            }
            // Jika respons ok, lanjutkan seperti biasa
            return response.json();
          })
          .then(data => {
            if (data.success) {
              const emotion = data.emotion.toLowerCase();
              const analysis = moodAnalysisData[emotion];

              if (analysis) {
                resultEmotion.textContent = `${data.emotion} ${analysis.emoji}`;
                resultAdvice.innerHTML = analysis.advice;
                resultActivities.innerHTML = analysis.activities.map(activity => `<li>${activity}</li>`).join('');
                analysisSection.classList.remove('hidden');
              }
            }
          })
          .catch(error => {
            console.error('Error:', error.message);
            // Tampilkan pesan error spesifik dari server ke pengguna
            alert(error.message);

            // Atur ulang tampilan secara otomatis karena gagal
            retakeBtn.click();
          })
          .finally(() => {
            loadingSpinner.classList.add('hidden');
          });
        // ===================================================
        // --- AKHIR BLOK PERBAIKAN PENANGANAN ERROR ---
        // ===================================================
      }, 'image/jpeg');
    });

    retakeBtn.addEventListener('click', () => {
      analysisSection.classList.add('hidden');
      capturedImage.classList.add('hidden');
      video.classList.remove('hidden');
      retakeBtn.classList.add('hidden');
      captureBtn.classList.remove('hidden');
      captureBtn.disabled = false;
    });
  </script>
  @endpush
</x-app-layout>