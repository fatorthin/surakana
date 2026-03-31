<div class="surface-card space-y-5 p-6">
    <form wire:submit="save" class="space-y-5">
        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <x-input-label for="pf-name" value="Nama Produk" />
                <x-text-input id="pf-name" wire:model="name" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="pf-price" value="Harga (Rp)" />
                <x-text-input id="pf-price" wire:model="price" type="number" step="0.01"
                    class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('price')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="pf-stock" value="Stok" />
                <x-text-input id="pf-stock" wire:model="stock" type="number" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('stock')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="pf-weight" value="Berat (cth: 250g)" />
                <x-text-input id="pf-weight" wire:model="weight" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('weight')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="pf-roast" value="Roast Level" />
                <x-text-input id="pf-roast" wire:model="roast_level" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('roast_level')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="pf-notes" value="Tasting Notes" />
                <x-text-input id="pf-notes" wire:model="tasting_notes" class="mt-1 block w-full" />
            </div>
        </div>

        <div>
            <x-input-label for="pf-image" value="URL Gambar" />
            <x-text-input id="pf-image" wire:model="image_url" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="pf-desc" value="Deskripsi" />
            <textarea id="pf-desc" wire:model="description" rows="5"
                class="mt-1 block w-full rounded-[1.25rem] border-[var(--line)] bg-white"></textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        <div class="flex flex-wrap gap-5">
            <label class="inline-flex items-center gap-2 text-sm text-[var(--ink)]">
                <input type="checkbox" wire:model="is_active"> Aktif
            </label>
            <label class="inline-flex items-center gap-2 text-sm text-[var(--ink)]">
                <input type="checkbox" wire:model="is_featured"> Unggulan
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="btn-ghost">Batal</a>
            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-60" class="btn-earth">
                <span wire:loading.remove>Simpan</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </div>
    </form>
</div>
