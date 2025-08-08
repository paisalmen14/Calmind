<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Analisis Suasana Hati') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">

          {{-- KONTEN HTML SURVEI (Tidak ada perubahan di sini) --}}
          <h1 class="text-2xl font-bold mb-4">Analisis Suasana Hati</h1>
          <p class="mb-2">Harap baca setiap pernyataan dan pilih angka (0, 1, 2, atau 3) yang paling menggambarkan seberapa sering pernyataan itu berlaku untuk Anda <strong>selama seminggu terakhir</strong>. Tidak ada jawaban yang benar atau salah.</p>
          <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-700">
            <p><strong>0</strong> = Tidak berlaku untuk saya sama sekali.</p>
            <p><strong>1</strong> = Berlaku untuk saya sampai tingkat tertentu, atau kadang-kadang.</p>
            <p><strong>2</strong> = Berlaku untuk saya sampai tingkat yang cukup besar, atau sering.</p>
            <p><strong>3</strong> = Sangat berlaku untuk saya, atau hampir setiap saat.</p>
          </div>

          <div id="result-container" style="display: none;" class="mt-8 p-6 text-center bg-green-50 border border-green-200 rounded-lg">
            <h2 class="text-xl font-bold">Hasil Analisis Anda</h2>
            <p class="text-lg mt-2"><strong>Tingkat Stres Terdeteksi:</strong> <span id="prediction-text" class="font-bold"></span></p>
            <h4 class="font-bold mt-4">Rekomendasi untuk Anda:</h4>
            <p id="recommendation-text" class="mt-2"></p>
            <button onclick="window.location.reload()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Isi Survei Lagi</button>
          </div>

          <form id="mood-survey-form">
            <div id="questions-wrapper" class="space-y-6"></div>
            <hr class="my-6">
            <button type="submit" id="submit-button" class="w-full px-4 py-3 bg-blue-600 text-white font-bold rounded hover:bg-blue-700 disabled:bg-gray-400">Kirim Jawaban & Lihat Hasil</button>
            <p id="error-message" class="text-red-600 bg-red-100 p-3 mt-4 rounded-lg text-center" style="display: none;"></p>
          </form>

        </div>
      </div>
    </div>
  </div>

  {{-- SCRIPT SECTION YANG DIPERBAIKI untuk resources/views/survey/index.blade.php --}}
  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const questions = [{
          id: 'Q1A',
          text: 'Saya merasa sulit untuk beristirahat.'
        },
        {
          id: 'Q2A',
          text: 'Saya menyadari mulut saya terasa kering.'
        },
        {
          id: 'Q3A',
          text: 'Saya tidak bisa merasakan perasaan positif sama sekali.'
        },
        {
          id: 'Q4A',
          text: 'Saya mengalami kesulitan bernapas (misalnya, napas yang terlalu cepat, sesak napas tanpa aktivitas fisik).'
        },
        {
          id: 'Q5A',
          text: 'Saya merasa sulit untuk berinisiatif melakukan sesuatu.'
        },
        {
          id: 'Q6A',
          text: 'Saya cenderung bereaksi berlebihan terhadap situasi.'
        },
        {
          id: 'Q7A',
          text: 'Saya mengalami gemetar (misalnya, di tangan).'
        },
        {
          id: 'Q8A',
          text: 'Saya merasa saya menggunakan banyak energi untuk hal-hal yang membuat cemas.'
        },
        {
          id: 'Q9A',
          text: 'Saya khawatir tentang situasi di mana saya mungkin panik dan mempermalukan diri sendiri.'
        },
        {
          id: 'Q10A',
          text: 'Saya merasa tidak ada hal yang bisa saya harapkan di masa depan.'
        },
        {
          id: 'Q11A',
          text: 'Saya mendapati diri saya menjadi gelisah.'
        },
        {
          id: 'Q12A',
          text: 'Saya merasa sulit untuk bersantai.'
        },
        {
          id: 'Q13A',
          text: 'Saya merasa sedih dan tertekan.'
        },
        {
          id: 'Q14A',
          text: 'Saya tidak bisa menoleransi apa pun yang menghalangi saya untuk melanjutkan apa yang sedang saya lakukan.'
        },
        {
          id: 'Q15A',
          text: 'Saya merasa hampir panik.'
        },
        {
          id: 'Q16A',
          text: 'Saya tidak bisa antusias tentang apa pun.'
        },
        {
          id: 'Q17A',
          text: 'Saya merasa diri saya tidak berharga.'
        },
        {
          id: 'Q18A',
          text: 'Saya merasa diri saya agak sensitif (mudah tersinggung).'
        },
        {
          id: 'Q19A',
          text: 'Saya menyadari detak jantung saya meskipun tidak beraktivitas fisik (misalnya, detak jantung meningkat, detak jantung tidak beraturan).'
        },
        {
          id: 'Q20A',
          text: 'Saya merasa takut tanpa alasan yang jelas.'
        },
        {
          id: 'Q21A',
          text: 'Saya merasa hidup ini tidak berarti.'
        },
        {
          id: 'Q22A',
          text: 'Saya merasa sulit untuk menenangkan diri.'
        },
        {
          id: 'Q23A',
          text: 'Saya khawatir akan situasi yang membuat saya cemas dan lega ketika situasi itu berakhir.'
        },
        {
          id: 'Q24A',
          text: 'Saya kesulitan menelan.'
        },
        {
          id: 'Q25A',
          text: 'Saya tidak bisa menyelesaikan pekerjaan yang sedang saya lakukan karena saya tidak bisa berkonsentrasi pada hal itu.'
        },
        {
          id: 'Q26A',
          text: 'Saya merasa kehilangan minat pada hampir semua hal.'
        },
        {
          id: 'Q27A',
          text: 'Saya merasa sangat panik.'
        },
        {
          id: 'Q28A',
          text: 'Saya berkeringat secara nyata tanpa alasan (misalnya, keringat panas/dingin).'
        },
        {
          id: 'Q29A',
          text: 'Saya merasa sulit untuk tenang setelah sesuatu membuat saya kesal.'
        },
        {
          id: 'Q30A',
          text: 'Saya takut bahwa saya akan "terhambat" oleh tugas sepele yang tidak biasa.'
        },
        {
          id: 'Q31A',
          text: 'Saya tidak dapat merasakan kenikmatan dari hal-hal yang biasanya saya nikmati.'
        },
        {
          id: 'Q32A',
          text: 'Saya merasa sulit untuk menoleransi gangguan terhadap apa yang sedang saya lakukan.'
        },
        {
          id: 'Q33A',
          text: 'Saya berada dalam keadaan tegang secara saraf.'
        },
        {
          id: 'Q34A',
          text: 'Saya merasa putus asa tentang masa depan.'
        },
        {
          id: 'Q35A',
          text: 'Saya tidak sabaran.'
        },
        {
          id: 'Q36A',
          text: 'Saya mengalami gemetar di lengan dan kaki.'
        },
        {
          id: 'Q37A',
          text: 'Saya merasa sulit untuk membangkitkan perasaan positif tentang apa pun.'
        },
        {
          id: 'Q38A',
          text: 'Saya merasa bahwa saya mudah marah.'
        },
        {
          id: 'Q39A',
          text: 'Saya merasakan sensasi kesemutan atau mati rasa di jari tangan atau kaki.'
        },
        {
          id: 'Q40A',
          text: 'Saya merasa takut bahwa beberapa penyakit fisik yang tidak terdiagnosis membuat saya terlihat konyol.'
        },
        {
          id: 'Q41A',
          text: 'Saya merasa hidup ini tidak ada artinya.'
        },
        {
          id: 'Q42A',
          text: 'Saya mudah gelisah.'
        }
      ];

      const questionsWrapper = document.getElementById('questions-wrapper');

      // Generate questions
      questions.forEach((q, index) => {
        const questionBlock = document.createElement('div');
        questionBlock.className = 'p-4 bg-gray-50 rounded-lg';
        questionBlock.innerHTML = `
            <p class="font-medium text-gray-800">${index + 1}. ${q.text}</p>
            <div class="options mt-3 space-y-2">
                <label class="flex items-center">
                    <input type="radio" name="${q.id}" value="0" class="mr-2" required> 0 - Tidak pernah
                </label>
                <label class="flex items-center">
                    <input type="radio" name="${q.id}" value="1" class="mr-2" required> 1 - Kadang-kadang
                </label>
                <label class="flex items-center">
                    <input type="radio" name="${q.id}" value="2" class="mr-2" required> 2 - Sering
                </label>
                <label class="flex items-center">
                    <input type="radio" name="${q.id}" value="3" class="mr-2" required> 3 - Hampir selalu
                </label>
            </div>
        `;
        questionsWrapper.appendChild(questionBlock);
      });

      // Form submission handler
      const surveyForm = document.getElementById('mood-survey-form');
      surveyForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        const submitButton = document.getElementById('submit-button');
        const resultContainer = document.getElementById('result-container');
        const errorMessage = document.getElementById('error-message');

        submitButton.disabled = true;
        submitButton.textContent = 'Menganalisis...';
        errorMessage.style.display = 'none';

        // METODE PENGUMPULAN DATA YANG DIPERBAIKI
        const formData = new FormData(surveyForm);
        const answers = {};

        // Collect all form data
        for (let [key, value] of formData.entries()) {
          answers[key] = parseInt(value, 10);
        }

        console.log('Collected answers:', answers);
        console.log('Total questions answered:', Object.keys(answers).length);

        // Validation
        if (Object.keys(answers).length !== 42) {
          showError(`Harap jawab semua 42 pertanyaan. Anda baru menjawab ${Object.keys(answers).length} pertanyaan.`);
          return;
        }

        // Verify all expected questions are present
        const expectedQuestions = questions.map(q => q.id);
        const missingQuestions = expectedQuestions.filter(id => !(id in answers));

        if (missingQuestions.length > 0) {
          showError(`Pertanyaan yang belum dijawab: ${missingQuestions.join(', ')}`);
          return;
        }

        try {
          const csrfToken = document.querySelector('meta[name="csrf-token"]');
          const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          };

          // Add CSRF token if available (Laravel)
          if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
          }

          console.log('Sending to server:', {
            answers: answers
          });

          const response = await fetch("{{ route('api.mood.analyze') }}", {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({
              answers: answers
            })
          });

          const result = await response.json();
          console.log('Server response:', result);

          if (!response.ok) {
            throw new Error(result.error || `Server Error: ${response.status}`);
          }

          // Display results
          document.getElementById('prediction-text').textContent = result.prediction;
          document.getElementById('recommendation-text').textContent = result.recommendation;
          resultContainer.style.display = 'block';
          surveyForm.style.display = 'none';

        } catch (error) {
          console.error('Error:', error);
          showError(`Terjadi kesalahan: ${error.message}`);
        }
      });

      function showError(message) {
        const submitButton = document.getElementById('submit-button');
        const errorMessage = document.getElementById('error-message');
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
        submitButton.disabled = false;
        submitButton.textContent = 'Kirim Jawaban & Lihat Hasil';
      }
    });
  </script>
  @endpush
</x-app-layout>