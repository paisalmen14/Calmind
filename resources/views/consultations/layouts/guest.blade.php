{{-- resources/views/layouts/guest.blade.php (Versi Elegan Baru) --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Font Kustom dari Welcome Page --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
        .elegant-bg { background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); }
        .brand-gradient { background: linear-gradient(135deg, #E91E63 0%, #F44336 100%); }
        .soft-shadow { box-shadow: 0 20px 40px -5px rgba(0, 0, 0, 0.08); }
        .text-brand-pink { color: #E91E63; }
        .focus\:ring-brand-pink:focus {
            --tw-ring-color: #E91E63;
        }
        .focus\:border-brand-pink:focus {
            --tw-border-opacity: 1;
            border-color: #E91E63;
        }
    </style>
</head>
<body class="font-sans text-gray-800 elegant-bg">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        {{-- Logo --}}
        <div>
            <a href="/" class="flex items-center gap-3" aria-label="SafeTalk Homepage">
                <svg class="h-12 w-auto" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                   <path d="M24 4C12.95 4 4 12.95 4 24C4 35.05 12.95 44 24 44C35.05 44 44 35.05 44 24C44 12.95 35.05 4 24 4ZM24 42C14.06 42 6 33.94 6 24C6 14.06 14.06 6 24 6C33.94 6 42 14.06 42 24C42 33.94 33.94 42 24 42Z" fill="#F43F5E"/>
                   <path d="M24 18C21.24 18 19 20.24 19 23V25C19 27.76 21.24 30 24 30C26.76 30 29 27.76 29 25V23C29 20.24 26.76 18 24 18ZM24 28C22.34 28 21 26.66 21 25V23C21 21.34 22.34 20 24 20C25.66 20 27 21.34 27 23V25C27 26.66 25.66 28 24 28Z" fill="#E91E63"/>
                </svg>
                <span class="font-serif text-3xl font-bold text-gray-900">SafeTalk</span>
            </a>
        </div>

        {{-- Form Container --}}
        <div class="w-full sm:max-w-md mt-8 px-8 py-10 bg-white soft-shadow overflow-hidden sm:rounded-2xl border border-gray-100">
            {{ $slot }}
        </div>
    </div>
</body>
</html>