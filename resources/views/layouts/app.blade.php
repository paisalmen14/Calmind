<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="scroll-behavior: smooth;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Calmind') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @extends('master')

    <style>
        .brand-gradient {
            background: linear-gradient(135deg, #E91E63 0%, #F44336 100%);
        }

        .text-brand-pink {
            color: #E91E63;
        }

        .focus\:ring-brand-pink:focus {
            --tw-ring-color: #E91E63;
        }

        .focus\:border-brand-pink:focus {
            border-color: #E91E63;
        }

        .elegant-bg {
            background-color: #f8fafc;
        }
    </style>
</head>

<body class="antialiased bg-[#FEFBC7]">
    <div>
        @include('layouts.navigation')

        <main>
            {{ $slot }}
        </main>
    </div>
    @stack('scripts')
</body>

</html>