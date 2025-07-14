{{-- resources/views/layouts/app.blade.php (Versi Elegan Baru) --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="scroll-behavior: smooth;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Font Kustom --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Style Tambahan untuk Konsistensi Tema --}}
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
        .brand-gradient { background: linear-gradient(135deg, #E91E63 0%, #F44336 100%); }
        .text-brand-pink { color: #E91E63; }
        .focus\:ring-brand-pink:focus { --tw-ring-color: #E91E63; }
        .focus\:border-brand-pink:focus { border-color: #E91E63; }
        .elegant-bg { background-color: #f8fafc; } /* Latar belakang abu-abu sangat muda */
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen elegant-bg">
        @include('layouts.navigation')

        @if (isset($header))
            <header class="bg-white shadow-sm border-b border-gray-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>