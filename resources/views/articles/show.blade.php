@extends('layouts.storefront')

@section('title', $article->title)

@section('content')
    <article class="section-shell pt-10 pb-20">
        <div class="mx-auto max-w-4xl">
            <p class="eyebrow">Jurnal Surakana</p>
            <h1 class="mt-2 font-heading text-5xl text-[var(--coffee)]">{{ $article->title }}</h1>
            <p class="mt-4 text-sm uppercase tracking-[0.22em] text-[var(--muted)]">
                {{ optional($article->published_at)->translatedFormat('d M Y') }} · {{ $article->author->name }}</p>
            <img src="{{ $article->image_url ?: 'https://images.unsplash.com/photo-1459755486867-b55449bb39ff?auto=format&fit=crop&w=1400&q=80' }}"
                alt="{{ $article->title }}" class="mt-8 h-[420px] w-full rounded-[2rem] object-cover shadow-soft" />

            <div
                class="prose prose-stone mt-8 max-w-none rounded-[2rem] border border-[var(--line)] bg-white/90 p-8 leading-8 shadow-soft">
                {!! nl2br(e($article->content)) !!}
            </div>
        </div>
    </article>
@endsection
