<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#6f3b24">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="icon" href="{{ asset('surakana.png') }}" type="image/png">

    <title>{{ config('app.name', 'Surakana') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:600,700|manrope:400,500,600,700,800&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>

<body class="font-sans antialiased bg-[var(--canvas)] text-[var(--ink)]">
    <div
        class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(189,121,71,0.18),_transparent_32%),radial-gradient(circle_at_top_right,_rgba(46,84,67,0.14),_transparent_26%),var(--canvas)]">
        @include('layouts.navigation')

        @isset($header)
            <header class="border-b border-[var(--line)]/80 bg-[rgba(248,243,236,0.88)] backdrop-blur">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        @if (session('status'))
            <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-soft">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <main class="pb-24 md:pb-10">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>

</html>
