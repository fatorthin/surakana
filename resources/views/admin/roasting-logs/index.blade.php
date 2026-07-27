<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm uppercase tracking-[0.24em] text-[var(--muted)]">Admin / Roasting Log</p>
            <h1 class="font-heading text-3xl text-[var(--coffee)]">Catatan batch roasting</h1>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">

            {{-- Resume active session banner --}}
            @if ($activeSession)
                <div
                    class="flex items-center justify-between gap-4 rounded-2xl border border-amber-300 bg-amber-50 px-5 py-4 shadow-sm">
                    <div>
                        <p class="text-sm font-semibold text-amber-800">Ada batch yang sedang berjalan</p>
                        <p class="text-xs text-amber-700">
                            {{ $activeSession['roasterName'] }} &mdash; {{ $activeSession['beanName'] }}
                        </p>
                    </div>
                    <a href="{{ route('admin.roasting-logs.session') }}" class="btn-earth shrink-0">Lanjutkan</a>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800 shadow-soft">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Setup form --}}
            <div class="surface-card overflow-hidden">
                <div class="flex items-center gap-3 border-b border-[var(--line)] bg-[var(--coffee)] px-6 py-4">
                    <svg class="h-5 w-5 text-[var(--accent)]" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M17 8C8 10 5.9 16.17 3.82 19.5c-.13.22-.05.49.18.6l1.31.61c.22.1.49.03.61-.19C7.24 18.28 8 16 10 14c-1 4 .5 6 2 8 .2.29.6.29.8 0C15.14 19 17.5 16 17 11c2 2 2.5 4 2.27 6.38-.04.38.27.72.65.65l1.45-.26c.34-.06.57-.39.52-.73C21.33 13.07 19.35 7.4 17 8z" />
                    </svg>
                    <p class="font-heading text-lg tracking-wide text-white">Mulai Batch Baru</p>
                </div>

                <div class="p-6 sm:p-8">
                    @if ($errors->any())
                        <div
                            class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.roasting-logs.session.start') }}" class="space-y-5">
                        @csrf

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="rl-roaster" value="Nama Roaster *" />
                                <x-text-input id="rl-roaster" name="roaster_name" :value="old('roaster_name')" list="roasterList"
                                    class="mt-1 block w-full" placeholder="Operator" required />
                                <datalist id="roasterList">
                                    @foreach ($roasterNames as $name)
                                        <option value="{{ $name }}"></option>
                                    @endforeach
                                </datalist>
                                <x-input-error :messages="$errors->get('roaster_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="rl-bean" value="Nama Biji *" />
                                <x-text-input id="rl-bean" name="bean_name" :value="old('bean_name')"
                                    class="mt-1 block w-full" placeholder="Gayo Aceh" required />
                                <x-input-error :messages="$errors->get('bean_name')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <x-input-label for="rl-origin" value="Origin" />
                                <x-text-input id="rl-origin" name="origin" :value="old('origin')" class="mt-1 block w-full"
                                    placeholder="Aceh, Indonesia" />
                                <x-input-error :messages="$errors->get('origin')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="rl-varietas" value="Varietas" />
                                <x-text-input id="rl-varietas" name="varietas" :value="old('varietas')"
                                    class="mt-1 block w-full" placeholder="Arabica, Robusta..." />
                                <x-input-error :messages="$errors->get('varietas')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="rl-process" value="Proses" />
                                <x-text-input id="rl-process" name="process_method" :value="old('process_method')"
                                    class="mt-1 block w-full" placeholder="Full Wash, Natural..." />
                                <x-input-error :messages="$errors->get('process_method')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="rl-green" value="Berat Green Bean (gram) *" />
                                <x-text-input id="rl-green" name="green_weight" :value="old('green_weight')" type="number"
                                    step="0.01" class="mt-1 block w-full" placeholder="1000" required />
                                <x-input-error :messages="$errors->get('green_weight')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="rl-charge" value="Suhu Charge (°C) *" />
                                <x-text-input id="rl-charge" name="charge_temp" :value="old('charge_temp')" type="number"
                                    step="0.01" class="mt-1 block w-full" placeholder="200" required />
                                <x-input-error :messages="$errors->get('charge_temp')" class="mt-2" />
                            </div>
                        </div>

                        <button type="submit" class="btn-earth inline-flex items-center gap-2 px-6">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                            Mulai Roasting
                        </button>
                    </form>
                </div>
            </div>

            {{-- History --}}
            <livewire:admin.roast-log-manager />

        </div>
    </div>
</x-app-layout>
