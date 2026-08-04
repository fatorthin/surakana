<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#6f3b24">
    <meta name="description" content="PWA ecommerce dan dashboard mini untuk home coffee roastery.">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="icon" href="{{ asset('surakana.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('surakana.png') }}">
    <title>@yield('title', config('app.name', 'Surakana'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:600,700|manrope:400,500,600,700,800&display=swap"
        rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-[var(--canvas)] text-[var(--ink)] antialiased">

    <div class="relative overflow-hidden">
        <div
            class="pointer-events-none absolute inset-x-0 top-0 h-[32rem] bg-[radial-gradient(circle_at_top_left,_rgba(189,121,71,0.28),_transparent_42%),radial-gradient(circle_at_top_right,_rgba(46,84,67,0.22),_transparent_36%)]">
        </div>

        <header class="relative z-10 border-b border-[var(--line)]/70 bg-[rgba(248,243,236,0.88)] backdrop-blur-md">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <x-application-logo class="h-12 w-auto" />
                    <div>
                        <p class="font-heading text-xl tracking-[0.18em] text-[var(--coffee)]">SURAKANA</p>
                        <p class="text-xs uppercase tracking-[0.28em] text-[var(--muted)]">home roastery</p>
                    </div>
                </a>

                <nav class="hidden items-center gap-6 text-sm font-semibold md:flex">
                    <a class="nav-chip" href="{{ route('catalog.index') }}">Katalog</a>
                    <a class="nav-chip" href="{{ route('articles.index') }}">Jurnal</a>
                    <a class="nav-chip" href="{{ url('/link') }}" target="_blank" rel="noopener noreferrer">Link</a>
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a class="nav-chip" href="{{ route('admin.dashboard') }}">Admin</a>
                        @else
                            <a class="nav-chip" href="{{ route('orders.index') }}">Pesanan Saya</a>
                        @endif
                    @endauth
                </nav>

                <div class="flex shrink-0 items-center gap-1.5 sm:gap-3">
                    {{-- <livewire:cart-badge /> --}}

                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('orders.index') }}"
                            class="btn-earth hidden sm:inline-flex">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-[var(--line)] bg-white text-[var(--coffee)] shadow-soft transition hover:-translate-y-0.5 flex-shrink-0"
                            title="Masuk">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M13 3H4a1 1 0 00-1 1v16a1 1 0 001 1h9v-2H4V4h9V3zm9.293 9.293l-3-3a1 1 0 10-1.414 1.414L19.586 11H12v2h7.586l-2.293 2.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414z" />
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        @if (session('status'))
            <div class="relative z-10 mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-soft">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <main class="relative z-10">
            @yield('content')
        </main>

        <footer class="relative z-10 mt-16 border-t border-[var(--line)] bg-[rgba(77,46,33,0.98)] text-[var(--sand)]">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[1.2fr,0.8fr] lg:px-8">
                <div>
                    <p class="font-heading text-2xl">
                        {{ $siteSettings['hero_title'] ?? 'Small batch coffee roasted with intention.' }}</p>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-[rgba(248,243,236,0.78)]">
                        {{ $siteSettings['about_text'] ?? 'Home roastery dengan batch kecil, roast segar, dan pendekatan rasa yang konsisten.' }}
                    </p>
                </div>
                <div class="grid text-sm">
                    <p>WhatsApp: {{ $siteSettings['contact_whatsapp'] ?? '-' }}</p>
                    <p>Instagram: {{ $siteSettings['contact_instagram'] ?? '-' }}</p>
                </div>
            </div>
        </footer>
    </div>

    <nav
        class="fixed inset-x-0 bottom-4 z-20 mx-auto flex w-[calc(100%-1.5rem)] max-w-md items-center justify-between rounded-[1.75rem] border border-[var(--line)] bg-[rgba(250,247,241,0.92)] px-3 py-2 shadow-[0_20px_45px_rgba(43,25,18,0.16)] backdrop-blur md:hidden">
        <a href="{{ route('home') }}"
            class="bottom-link {{ request()->routeIs('home') ? 'bottom-link-active' : '' }}">Home</a>
        <a href="{{ route('catalog.index') }}"
            class="bottom-link {{ request()->routeIs('catalog.*') ? 'bottom-link-active' : '' }}">Katalog</a>
        <a href="{{ route('articles.index') }}"
            class="bottom-link {{ request()->routeIs('articles.*') ? 'bottom-link-active' : '' }}">Jurnal</a>
        <a href="{{ route('cart.index') }}"
            class="bottom-link {{ request()->routeIs('cart.*') ? 'bottom-link-active' : '' }}">Cart</a>
    </nav>

    @livewireScripts
</body>

</html>
