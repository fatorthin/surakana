<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#6f3b24">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <title>{{ config('app.name', 'Surakana') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:600,700|manrope:400,500,600,700,800&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-[var(--ink)] antialiased bg-[var(--canvas)]">
    <div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden px-4 py-10 sm:px-6">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(189,121,71,0.28),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(46,84,67,0.2),_transparent_30%)]">
        </div>

        <div class="relative z-10 mb-6 text-center">
            <a href="/" class="inline-flex flex-col items-center gap-3">
                <span
                    class="flex h-20 w-20 items-center justify-center rounded-[2rem] bg-[var(--coffee)] text-[var(--canvas)] shadow-[0_18px_40px_rgba(55,30,20,0.18)]">
                    <x-application-logo class="h-10 w-10" />
                </span>
                <div>
                    <p class="font-heading text-2xl tracking-[0.18em] text-[var(--coffee)]">SURAKANA</p>
                    <p class="text-xs uppercase tracking-[0.28em] text-[var(--muted)]">craft coffee portal</p>
                </div>
            </a>
        </div>

        <div
            class="relative z-10 w-full max-w-md overflow-hidden rounded-[2rem] border border-[var(--line)] bg-[rgba(255,252,247,0.92)] px-6 py-6 shadow-[0_25px_70px_rgba(55,30,20,0.12)] backdrop-blur">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
