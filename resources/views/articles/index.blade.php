@extends('layouts.storefront')

@section('title', 'Jurnal Surakana')

@section('content')
    <section class="section-shell pt-10 pb-20">
        <div class="mb-8 max-w-3xl">
            <p class="eyebrow">Artikel & Edukasi</p>
            <h1 class="mt-2 font-heading text-5xl text-[var(--coffee)]">Catatan batch, teknik seduh, dan cerita dari
                roastery.</h1>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($articles as $article)
                <article class="surface-card overflow-hidden">
                    <img src="{{ $article->image_url ?: 'https://images.unsplash.com/photo-1459755486867-b55449bb39ff?auto=format&fit=crop&w=900&q=80' }}"
                        alt="{{ $article->title }}" class="h-60 w-full object-cover" />
                    <div class="p-6">
                        <p class="text-xs uppercase tracking-[0.22em] text-[var(--muted)]">
                            {{ optional($article->published_at)->translatedFormat('d M Y') }}</p>
                        <h2 class="mt-3 font-heading text-2xl text-[var(--coffee)]">{{ $article->title }}</h2>
                        <p class="mt-3 text-sm leading-7 text-[var(--muted)]">{{ $article->excerpt }}</p>
                        <a href="{{ route('articles.show', $article) }}"
                            class="mt-5 inline-flex text-sm font-semibold text-[var(--accent-deep)]">Baca artikel</a>
                    </div>
                </article>
            @empty
                <div class="surface-card p-8 text-[var(--muted)] md:col-span-2 xl:col-span-3">Belum ada artikel terbit.
                </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $articles->links() }}</div>
    </section>
@endsection
