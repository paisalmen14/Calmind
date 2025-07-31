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
    @extends('master')
    {{-- @extends('master') --}} {{-- Catatan: @extends biasanya tidak ditaruh di <head> --}}

    <style>
        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        .font-sans {
            font-family: 'Inter', sans-serif;
        }

        .brand-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .text-brand-purple {
            color: #764ba2;
        }

        .bg-brand-light {
            background-color: #f7f7ff;
        }
    </style>
</head>

<body class="font-sans antialiased text-slate-800 bg-[#FEFBC7]">
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
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2 text-sm font-medium text-slate-600 hover:text-brand-purple transition-colors">Ruang Cerita</a>
                    @else
                    <a href="{{ route('login') }}" class="font-gabarito px-3 py-2 text-sm font-medium text-black hover:text-[#E14434] transition-colors">Log in</a>
                    <a href="{{ route('register') }}" class="font-gabarito px-6 py-2 text-sm font-medium text-black bg-[#FFB4B4] rounded-full hover:opacity-70 transition-colors">Register</a>
                    @endauth
                    @endif
                </nav>
            </div>
        </header>

        <main class="flex-grow">
            {{-- Bagian Hero Section --}}
            <section class="relative pt-16 pb-16 lg:pt-24 lg:pb-24 overflow-hidden">
                <div class="max-w-screen-xl mx-auto
                    flex flex-col lg:flex-row items-center justify-center gap-8 lg:gap-16
                    px-4 md:px-8 lg:px-12">
                    <div class="max-w-xl text-center lg:text-left flex-shrink-0">
                        <h1 class="font-londrina text-4xl sm:text-5xl lg:text-6xl font-extrabold text-black leading-tight tracking-wide">
                            Temukan Ruang Aman untuk <span class="text-[#5EABD6]">Berbagi Cerita.</span>
                        </h1>
                        <p class="font-quicksand mt-6 max-w-2xl mx-auto text-lg text-black">
                            Anda tidak sendirian. Di Calmind, kami menyediakan platform suportif untuk kesehatan mental Anda, menghubungkan Anda dengan komunitas dan para profesional yang peduli.
                        </p>
                        <div class="font-gabarito mt-8 flex justify-center lg:justify-start gap-4">
                            <a href="{{ route('login') }}" class="px-6 py-2 bg-[#FFB4B4] text-black rounded-full hover:opacity-70 transition-opacity whitespace-nowrap">
                                Mulai Sekarang
                            </a>
                            <a href="#fitur" class="px-4 py-2 text-white bg-black rounded-full border border-slate-200 hover:opacity-70 transition-colors whitespace-nowrap">
                                Lihat Fitur
                            </a>
                        </div>
                    </div>

                    <div class="relative w-full max-w-md lg:max-w-lg mt-8 lg:mt-0 p-4
                        bg-[#E14434] border-[8px] border-[#5EABD6] rounded-[60px] overflow-hidden flex-shrink-0 min-h-[300px] lg:min-h-[400px]">
                        <img src="/images/Asset_1.png" alt="Ilustrasi Ruang Aman Calmind" class="absolute bottom-0 left-1/2 -translate-x-1/2 
                        w-full h-auto object-cover rounded-xl">
                    </div>
                </div>
            </section>

            {{-- ====================================================== --}}
            {{-- KODE STATISTIK MOOD DITARUH DI SINI --}}
            {{-- ====================================================== --}}
            @auth
            {{-- Komponen ini hanya akan tampil jika pengguna sudah login --}}
            <section class="pb-12 -mt-12">
                <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                    <div class="mb-8">
                        <x-mood-stats :moodHistories="$moodHistories" :moodStats="$moodStats" :averageMood="$averageMood" />
                    </div>
                </div>
            </section>
            @endauth
            {{-- ====================================================== --}}


            {{-- Bagian Fitur --}}
            <section id="fitur" class="py-20 lg:py-24 bg-[#5EABD6]">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="font-londrina text-3xl lg:text-4xl text-black">Didesain untuk Ketenangan Anda</h2>
                        <p class="font-quicksand mt-4 max-w-2xl mx-auto text-lg text-black">
                            Jelajahi fitur-fitur yang kami siapkan untuk mendukung perjalanan kesehatan mental Anda.
                        </p>
                    </div>

                    <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-[#E14434] text-brand-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2V4a2 2 0 012-2h8z" />
                                </svg>
                            </div>
                            <h3 class="mt-5 text-xl font-londrina text-black">Ruang Cerita Anonim</h3>
                            <p class="font-quicksand mt-2 text-black">
                                Bagikan perasaan dan pengalaman Anda dengan aman dan anonim. Dapatkan dukungan dari komunitas yang memahami.
                            </p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-[#E14434] text-brand-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="mt-5 text-xl font-londrina text-black">Konsultasi Profesional</h3>
                            <p class="font-quicksand mt-2 text-black">
                                Terhubung langsung dengan psikolog berlisensi untuk mendapatkan bantuan dan bimbingan profesional.
                            </p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-[#E14434] text-brand-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h3 class="mt-5 text-xl font-londrina text-black">Jurnal & Curhat AI</h3>
                            <p class="font-quicksand mt-2 text-black">
                                Catat perjalanan emosional Anda setiap hari dan dapatkan umpan balik instan dari asisten AI kami.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>