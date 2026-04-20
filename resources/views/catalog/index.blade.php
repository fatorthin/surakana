@extends('layouts.storefront')

@section('title', 'Katalog Kopi')

@section('content')
    <section class="section-shell pt-10 pb-20">
        <div class="mb-8">
            <p class="eyebrow">Katalog</p>
            <h1 class="mt-2 font-heading text-5xl text-[var(--coffee)]">Pilih batch sesuai profil seduhanmu.</h1>
        </div>

        <livewire:catalog-browser />
    </section>
@endsection
