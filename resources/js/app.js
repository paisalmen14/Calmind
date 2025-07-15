import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// TAMBAHKAN KODE DI BAWAH INI
document.addEventListener('DOMContentLoaded', function () {
    const voteForms = document.querySelectorAll('.vote-form');

    voteForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Mencegah form refresh halaman

            const formData = new FormData(this);
            const actionUrl = this.getAttribute('action');
            const storyId = actionUrl.split('/')[4]; // Mendapatkan ID cerita dari URL

            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json', // Meminta respons JSON dari server
                }
            })
            .then(response => response.json())
            .then(data => {
                // Perbarui angka di halaman berdasarkan respons dari server
                document.getElementById(`upvotes-count-${storyId}`).textContent = data.upvotes_count;
                document.getElementById(`downvotes-count-${storyId}`).textContent = data.downvotes_count;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat melakukan vote.');
            });
        });
    });
});