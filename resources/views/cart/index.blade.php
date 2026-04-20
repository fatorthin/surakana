@extends('layouts.storefront')

@section('title', 'Keranjang')

@section('content')
    <section class="section-shell pt-10 pb-20">
        <div class="mb-8 max-w-3xl">
            <p class="eyebrow">Keranjang</p>
            <h1 class="mt-2 font-heading text-5xl text-[var(--coffee)]">Periksa pesanan sebelum checkout.</h1>
        </div>

        <livewire:cart-page />
    </section>
@endsection
