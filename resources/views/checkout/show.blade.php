@extends('layouts.storefront')

@section('title', 'Checkout')

@section('content')
    <section class="section-shell pt-10 pb-20">
        <div class="mb-8 max-w-3xl">
            <p class="eyebrow">Checkout</p>
            <h1 class="mt-2 font-heading text-5xl text-[var(--coffee)]">Lengkapi pengiriman, kami proses roasting berikutnya.
            </h1>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr,0.9fr]">
            <form action="{{ route('checkout.store') }}" method="POST" class="surface-card space-y-5 p-6">
                @csrf
                <div>
                    <x-input-label for="shipping_address" value="Alamat Pengiriman" />
                    <textarea id="shipping_address" name="shipping_address" rows="5"
                        class="mt-1 block w-full rounded-[1.25rem] border-[var(--line)] bg-white">{{ old('shipping_address') }}</textarea>
                    <x-input-error :messages="$errors->get('shipping_address')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="shipping_method" value="Metode Pengiriman" />
                    <select id="shipping_method" name="shipping_method"
                        class="mt-1 block w-full rounded-[1.25rem] border-[var(--line)] bg-white">
                        <option value="regular">Regular courier</option>
                        <option value="same-day">Same day local</option>
                        <option value="pickup">Pickup roastery</option>
                    </select>
                </div>

                <div>
                    <x-input-label for="customer_notes" value="Catatan" />
                    <textarea id="customer_notes" name="customer_notes" rows="4"
                        class="mt-1 block w-full rounded-[1.25rem] border-[var(--line)] bg-white">{{ old('customer_notes') }}</textarea>
                </div>

                <button class="btn-earth w-full justify-center">Buat Pesanan</button>
            </form>

            <aside class="surface-card p-6">
                <p class="eyebrow">Ringkasan Order</p>
                <div class="mt-5 space-y-4">
                    @foreach ($items as $item)
                        <div class="flex items-center justify-between gap-4 rounded-[1.25rem] bg-[var(--sand)] px-4 py-3">
                            <div>
                                <p class="font-semibold text-[var(--coffee)]">{{ $item['name'] }}</p>
                                <p class="text-sm text-[var(--muted)]">{{ $item['quantity'] }} x {{ $item['weight'] }}</p>
                            </div>
                            <span
                                class="font-semibold text-[var(--coffee)]">Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <div
                    class="mt-6 flex items-center justify-between border-t border-[var(--line)] pt-4 text-lg font-semibold text-[var(--coffee)]">
                    <span>Total</span>
                    <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </aside>
        </div>
    </section>
@endsection
