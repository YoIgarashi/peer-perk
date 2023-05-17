<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Tailwind -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
    @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP&family=Patua+One&display=swap');

    .font-family-karla {
        font-family: karla;
    }

    .font-sans {
        font-family: 'Noto Sans JP', sans-serif;
    }

    .font-patua {
        font-family: 'Patua One', cursive;
    }
    </style>
</head>

<body class="user-bg-gray font-sans">
    <header class="text-gray-600 bg-white">
        {{ $header_slot }}
    </header>
    {{ $body_slot }}
</body>
