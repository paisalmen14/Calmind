<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page_title', config('app.name', 'Calmind'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @extends('master')

    <!-- Styles -->
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
        .elegant-bg { background-color: #f8fafc; }
        .brand-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .text-brand-purple { color: #764ba2; }
        .focus\:ring-brand-purple:focus { --tw-ring-color: #764ba2; }
        .focus\:border-brand-purple:focus { border-color: #764ba2; }
        .soft-shadow { box-shadow: 0 20px 40px -5px rgba(0, 0, 0, 0.08); }
    </style>
</head>
<body class="font-sans text-gray-800 bg-[#FEFBC7]">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

        <!-- Form Container -->
        <div class="w-full sm:max-w-5xl mt-6 md:px-12 md:py-16 bg-white soft-shadow rounded-3xl">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
