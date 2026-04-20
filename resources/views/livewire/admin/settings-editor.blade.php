<div class="surface-card space-y-5 p-6">
    {{-- Success toast --}}
    @if ($saved)
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            Pengaturan berhasil disimpan.
        </div>
    @endif

    <form wire:submit="save" class="space-y-5">
        <div>
            <x-input-label for="s-hero-title" value="Hero Title" />
            <x-text-input id="s-hero-title" wire:model="hero_title" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('hero_title')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="s-hero-sub" value="Hero Subtitle" />
            <textarea id="s-hero-sub" wire:model="hero_subtitle" rows="3"
                class="mt-1 block w-full rounded-[1.25rem] border-[var(--line)] bg-white"></textarea>
        </div>

        <div>
            <x-input-label for="s-about" value="About Text" />
            <textarea id="s-about" wire:model="about_text" rows="4"
                class="mt-1 block w-full rounded-[1.25rem] border-[var(--line)] bg-white"></textarea>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <x-input-label for="s-wa" value="WhatsApp" />
                <x-text-input id="s-wa" wire:model="contact_whatsapp" class="mt-1 block w-full" />
            </div>
            <div>
                <x-input-label for="s-ig" value="Instagram" />
                <x-text-input id="s-ig" wire:model="contact_instagram" class="mt-1 block w-full" />
            </div>
        </div>

        <div>
            <x-input-label for="s-faq" value="FAQ" />
            <textarea id="s-faq" wire:model="faq" rows="6"
                class="mt-1 block w-full rounded-[1.25rem] border-[var(--line)] bg-white"></textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-60" class="btn-earth">
                <span wire:loading.remove>Simpan Pengaturan</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </div>
    </form>
</div>
