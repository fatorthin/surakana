@extends('layouts.storefront')

@section('title', 'Surakana Roastery')

@section('content')
    <section class="section-shell pt-10 sm:pt-16">
        <div class="grid items-center gap-8 lg:grid-cols-[1.1fr,0.9fr] lg:gap-12">
            <div>
                <p class="eyebrow">PWA E-commerce untuk Home Roastery Mantap Sekali</p>
                <h1 class="mt-4 max-w-3xl font-heading text-5xl leading-[1.02] text-[var(--coffee)] sm:text-6xl">
                    {{ $siteSettings['hero_title'] ?? 'Small batch coffee roasted with intention.' }}</h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-[var(--muted)] sm:text-lg">
                    {{ $siteSettings['hero_subtitle'] ?? 'Kopi sangrai harian dengan karakter rasa yang jernih, hangat, dan konsisten untuk rumah maupun kedai kecil.' }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('catalog.index') }}" class="btn-earth">Belanja Biji Kopi Guys</a>
                    <a href="{{ route('articles.index') }}" class="btn-ghost">Baca Jurnal</a>
                </div>
                <div class="mt-10 grid gap-4 sm:grid-cols-3">
                    <div class="mini-stat">
                        <span>Fresh roast</span>
                        <strong>Batch kecil</strong>
                    </div>
                    <div class="mini-stat">
                        <span>Mobile first</span>
                        <strong>Checkout cepat</strong>
                    </div>
                    <div class="mini-stat">
                        <span>PWA ready</span>
                        <strong>Installable</strong>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -left-6 top-6 hidden h-24 w-24 rounded-full bg-[var(--accent)]/20 blur-2xl sm:block">
                </div>
                <div class="surface-card overflow-hidden p-4 sm:p-5">
                    <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1400&q=80"
                        alt="Roasting coffee" class="h-[420px] w-full rounded-[1.8rem] object-cover" />
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-[1.4rem] bg-[var(--sand)] p-4">
                            <p class="text-xs uppercase tracking-[0.24em] text-[var(--muted)]">Profil rasa</p>
                            <p class="mt-2 font-semibold text-[var(--coffee)]">Sweet, clean, structured.</p>
                        </div>
                        <div class="rounded-[1.4rem] bg-[var(--forest)] p-4 text-[var(--sand)]">
                            <p class="text-xs uppercase tracking-[0.24em] text-[rgba(248,243,236,0.7)]">Kontak cepat</p>
                            <p class="mt-2 font-semibold">{{ $siteSettings['contact_whatsapp'] ?? 'WhatsApp tersedia' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-shell mt-16">
        <div class="grid gap-5 md:grid-cols-3">
            <div class="surface-card p-6">
                <p class="eyebrow">Freshness</p>
                <h2 class="mt-3 font-heading text-3xl text-[var(--coffee)]">Roast date jelas, rest time terjaga.</h2>
            </div>
            <div class="surface-card p-6">
                <p class="eyebrow">Craft</p>
                <h2 class="mt-3 font-heading text-3xl text-[var(--coffee)]">Profil dibuat untuk rumah, bukan sekadar
                    produksi massal.</h2>
            </div>
            <div class="surface-card p-6">
                <p class="eyebrow">Support</p>
                <h2 class="mt-3 font-heading text-3xl text-[var(--coffee)]">Artikel edukasi dan update batch selalu
                    tersedia.</h2>
            </div>
        </div>
    </section>

    <section class="section-shell mt-16">
        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <p class="eyebrow">Produk Unggulan</p>
                <h2 class="mt-2 font-heading text-4xl text-[var(--coffee)]">Kopi yang sedang kami dorong minggu ini.</h2>
            </div>
            <a href="{{ route('catalog.index') }}" class="btn-ghost">Lihat katalog</a>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            @foreach ($featuredProducts as $product)
                <article class="product-card">
                    <img src="{{ $product->image_url ?: 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=900&q=80' }}"
                        alt="{{ $product->name }}" class="h-64 w-full rounded-[1.6rem] object-cover" />
                    <div class="mt-5 flex items-start justify-between gap-3">
                        <div>
                            <p class="pill">{{ $product->roast_level }}</p>
                            <h3 class="mt-3 font-heading text-2xl text-[var(--coffee)]">{{ $product->name }}</h3>
                            <p class="mt-2 text-sm leading-7 text-[var(--muted)]">{{ $product->tasting_notes }}</p>
                        </div>
                        <p class="text-right font-semibold text-[var(--coffee)]">
                            Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>

                    <livewire:add-to-cart :product="$product" :key="'home-atc-' . $product->id" />
                </article>
            @endforeach
        </div>
    </section>

    <section class="section-shell mt-16 pb-20">
        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <p class="eyebrow">Jurnal Roastery</p>
                <h2 class="mt-2 font-heading text-4xl text-[var(--coffee)]">Catatan roasting, edukasi, dan update batch.
                </h2>
            </div>
            <a href="{{ route('articles.index') }}" class="btn-ghost">Semua artikel</a>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            @foreach ($latestArticles as $article)
                <article class="surface-card overflow-hidden">
                    <img src="{{ $article->image_url ?: 'https://images.unsplash.com/photo-1459755486867-b55449bb39ff?auto=format&fit=crop&w=900&q=80' }}"
                        alt="{{ $article->title }}" class="h-56 w-full object-cover" />
                    <div class="p-6">
                        <p class="text-xs uppercase tracking-[0.22em] text-[var(--muted)]">
                            {{ optional($article->published_at)->translatedFormat('d M Y') }}</p>
                        <h3 class="mt-3 font-heading text-2xl text-[var(--coffee)]">{{ $article->title }}</h3>
                        <p class="mt-3 text-sm leading-7 text-[var(--muted)]">{{ $article->excerpt }}</p>
                        <a href="{{ route('articles.show', $article) }}"
                            class="mt-5 inline-flex text-sm font-semibold text-[var(--accent-deep)]">Baca selengkapnya</a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
