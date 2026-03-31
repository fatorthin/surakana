<div class="surface-card space-y-5 p-6">
    <form wire:submit="save" class="space-y-5">
        <div>
            <x-input-label for="af-title" value="Judul" />
            <x-text-input id="af-title" wire:model="title" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="af-excerpt" value="Excerpt" />
            <x-text-input id="af-excerpt" wire:model="excerpt" class="mt-1 block w-full" />
        </div>

        <div>
            <x-input-label for="af-image" value="URL Gambar" />
            <x-text-input id="af-image" wire:model="image_url" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="af-content" value="Konten" />
            <textarea id="af-content" wire:model="content" rows="12"
                class="mt-1 block w-full rounded-[1.25rem] border-[var(--line)] bg-white"></textarea>
            <x-input-error :messages="$errors->get('content')" class="mt-2" />
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <x-input-label for="af-published-at" value="Tanggal Publish" />
                <x-text-input id="af-published-at" wire:model="published_at" type="datetime-local"
                    class="mt-1 block w-full" />
            </div>
            <label class="inline-flex items-center gap-2 pt-8 text-sm text-[var(--ink)]">
                <input type="checkbox" wire:model="is_published"> Publish artikel
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.articles.index') }}" class="btn-ghost">Batal</a>
            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-60" class="btn-earth">
                <span wire:loading.remove>Simpan</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </div>
    </form>
</div>
