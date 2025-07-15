<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-g">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calmind - Ruang Aman untuk Kesehatan Mental Anda</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
        .brand-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .text-brand-purple { color: #764ba2; }
        .bg-brand-light { background-color: #f7f7ff; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-white">
    <div id="app" class="flex flex-col min-h-screen">
        
        <header class="w-full py-4 px-4 sm:px-6 lg:px-8 absolute top-0 z-10">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <a href="/" class="flex items-center gap-2">
                    <x-application-logo class="w-8 h-8 text-brand-purple" />
                    <span class="font-serif text-2xl font-bold text-slate-900">Calmind</span>
                </a>

                <nav class="hidden md:flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2 text-sm font-medium text-slate-600 hover:text-brand-purple transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-medium text-slate-600 hover:text-brand-purple transition-colors">Log in</a>
                            <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-medium text-white bg-slate-800 rounded-full hover:bg-slate-900 transition-colors">Register</a>
                        @endauth
                    @endif
                </nav>
            </div>
        </header>

        <main class="flex-grow">
            <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-28 text-center bg-brand-light overflow-hidden">
                <div class="max-w-4xl mx-auto px-4">
                    <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight">
                        Temukan Ruang Aman untuk <span class="text-brand-purple">Berbagi Cerita.</span>
                    </h1>
                    <p class="mt-6 max-w-2xl mx-auto text-lg text-slate-600">
                        Anda tidak sendirian. Di Calmind, kami menyediakan platform suportif untuk kesehatan mental Anda, menghubungkan Anda dengan komunitas dan para profesional yang peduli.
                    </p>
                    <div class="mt-8 flex justify-center gap-4">
                        <a href="{{ route('register') }}" class="px-8 py-3 font-semibold text-white rounded-full brand-gradient hover:opacity-90 transition-opacity">
                            Mulai Sekarang
                        </a>
                        <a href="#fitur" class="px-8 py-3 font-semibold text-slate-700 bg-white rounded-full border border-slate-200 hover:bg-slate-50 transition-colors">
                            Lihat Fitur
                        </a>
                    </div>
                </div>
            </section>

            <section id="fitur" class="py-20 lg:py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="font-serif text-3xl lg:text-4xl font-bold text-slate-900">Didesain untuk Ketenangan Anda</h2>
                        <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-500">
                            Jelajahi fitur-fitur yang kami siapkan untuk mendukung perjalanan kesehatan mental Anda.
                        </p>
                    </div>

                    <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-brand-light text-brand-purple">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2V4a2 2 0 012-2h8z" /></svg>
                            </div>
                            <h3 class="mt-5 text-xl font-semibold text-slate-900">Ruang Cerita Anonim</h3>
                            <p class="mt-2 text-slate-500">
                                Bagikan perasaan dan pengalaman Anda dengan aman dan anonim. Dapatkan dukungan dari komunitas yang memahami.
                            </p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-brand-light text-brand-purple">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <h3 class="mt-5 text-xl font-semibold text-slate-900">Konsultasi Profesional</h3>
                            <p class="mt-2 text-slate-500">
                                Terhubung langsung dengan psikolog berlisensi untuk mendapatkan bantuan dan bimbingan profesional.
                            </p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-brand-light text-brand-purple">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            </div>
                            <h3 class="mt-5 text-xl font-semibold text-slate-900">Jurnal & Konsultasi AI</h3>
                            <p class="mt-2 text-slate-500">
                                Catat perjalanan emosional Anda setiap hari dan dapatkan umpan balik instan dari asisten AI kami.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

             <section class="bg-slate-800 py-20 lg:py-24">
                <div class="max-w-2xl mx-auto text-center px-4">
                    <h2 class="font-serif text-3xl lg:text-4xl font-bold text-white">Langkah Pertama Menuju Ketenangan</h2>
                    <p class="mt-4 text-lg text-slate-300">
                        Bergabunglah dengan komunitas Calmind hari ini. Registrasi gratis dan mulailah perjalanan Anda.
                    </p>
                    <div class="mt-8">
                        <a href="{{ route('register') }}" class="px-8 py-3 font-semibold text-slate-900 bg-white rounded-full hover:bg-slate-100 transition-colors">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </section>
        </main>

        <footer class="bg-white">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center text-sm text-slate-500">
                <p>&copy; {{ date('Y') }} Calmind. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>