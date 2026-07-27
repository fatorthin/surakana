@php($isAdmin = auth()->user()->isAdmin())
@php($cartSnapshot = \App\Http\Controllers\CartController::cartSnapshot(request()))

<nav x-data="{ open: false }"
    class="relative z-10 border-b border-[var(--line)]/80 bg-[rgba(248,243,236,0.88)] backdrop-blur-md">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-18 justify-between gap-4 py-3">
            <div class="flex items-center gap-4">
                <div class="shrink-0 flex items-center">
                    <a href="{{ $isAdmin ? route('admin.dashboard') : route('home') }}" class="flex items-center gap-3">
                        <span
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[var(--coffee)] text-[var(--canvas)] shadow-soft">
                            <x-application-logo class="block h-6 w-6" />
                        </span>
                        <div>
                            <p class="font-heading text-lg tracking-[0.18em] text-[var(--coffee)]">SURAKANA</p>
                            <p class="text-[10px] uppercase tracking-[0.25em] text-[var(--muted)]">
                                {{ $isAdmin ? 'admin console' : 'customer portal' }}</p>
                        </div>
                    </a>
                </div>

                <div class="hidden items-center gap-3 md:flex">
                    @if ($isAdmin)
                        <a class="nav-chip {{ request()->routeIs('admin.dashboard') ? 'nav-chip-active' : '' }}"
                            href="{{ route('admin.dashboard') }}">Ringkasan</a>
                        <a class="nav-chip {{ request()->routeIs('admin.products.*') ? 'nav-chip-active' : '' }}"
                            href="{{ route('admin.products.index') }}">Produk</a>
                        <a class="nav-chip {{ request()->routeIs('admin.orders.*') ? 'nav-chip-active' : '' }}"
                            href="{{ route('admin.orders.index') }}">Pesanan</a>
                        <a class="nav-chip {{ request()->routeIs('admin.finances.*') ? 'nav-chip-active' : '' }}"
                            href="{{ route('admin.finances.index') }}">Keuangan</a>
                        <a class="nav-chip {{ request()->routeIs('admin.roasting-logs.*') ? 'nav-chip-active' : '' }}"
                            href="{{ route('admin.roasting-logs.index') }}">Roasting Log</a>
                        <a class="nav-chip {{ request()->routeIs('admin.articles.*') ? 'nav-chip-active' : '' }}"
                            href="{{ route('admin.articles.index') }}">Konten</a>
                        <a class="nav-chip {{ request()->routeIs('admin.settings.*') ? 'nav-chip-active' : '' }}"
                            href="{{ route('admin.settings.edit') }}">Pengaturan</a>
                    @else
                        <a class="nav-chip {{ request()->routeIs('home') ? 'nav-chip-active' : '' }}"
                            href="{{ route('home') }}">Home</a>
                        <a class="nav-chip {{ request()->routeIs('catalog.*') ? 'nav-chip-active' : '' }}"
                            href="{{ route('catalog.index') }}">Katalog</a>
                        <a class="nav-chip {{ request()->routeIs('orders.*') ? 'nav-chip-active' : '' }}"
                            href="{{ route('orders.index') }}">Pesanan</a>
                        <a class="nav-chip {{ request()->routeIs('profile.*') ? 'nav-chip-active' : '' }}"
                            href="{{ route('profile.edit') }}">Profil</a>
                    @endif
                </div>
            </div>

            <div class="hidden items-center gap-3 sm:flex sm:ms-6">
                @unless ($isAdmin)
                    <a href="{{ route('cart.index') }}"
                        class="relative inline-flex min-h-11 min-w-11 items-center justify-center rounded-2xl border border-[var(--line)] bg-white px-4 text-sm font-semibold text-[var(--coffee)] shadow-soft transition hover:-translate-y-0.5">
                        Cart
                        @if ($cartSnapshot['count'] > 0)
                            <span
                                class="absolute -right-1 -top-1 flex h-6 min-w-6 items-center justify-center rounded-full bg-[var(--accent)] px-1 text-xs font-bold text-white">{{ $cartSnapshot['count'] }}</span>
                        @endif
                    </a>
                @endunless

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-3 rounded-2xl border border-[var(--line)] bg-white px-3 py-2 text-sm font-medium text-[var(--coffee)] shadow-soft transition hover:text-[var(--ink)] focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profil
                        </x-dropdown-link>

                        @unless ($isAdmin)
                            <x-dropdown-link :href="route('orders.index')">
                                Pesanan Saya
                            </x-dropdown-link>
                        @endunless

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center rounded-2xl border border-[var(--line)] bg-white p-3 text-[var(--coffee)] shadow-soft transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="space-y-2 border-t border-[var(--line)] px-4 py-4">
            @if ($isAdmin)
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Ringkasan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">Produk</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">Pesanan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.finances.index')" :active="request()->routeIs('admin.finances.*')">Keuangan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.roasting-logs.index')" :active="request()->routeIs('admin.roasting-logs.*')">Roasting Log</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.articles.index')" :active="request()->routeIs('admin.articles.*')">Konten</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.settings.edit')" :active="request()->routeIs('admin.settings.*')">Pengaturan</x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">Home</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.*')">Katalog</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">Pesanan Saya</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">Cart</x-responsive-nav-link>
            @endif
        </div>

        <div class="border-t border-[var(--line)] px-4 py-4">
            <div class="px-4">
                <div class="font-medium text-base text-[var(--ink)]">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-[var(--muted)]">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profil
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Keluar
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
