<div>
    {{-- Notification --}}
    @if ($notification)
        <div
            class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ $notification }}
        </div>
    @endif

    <div class="mb-5 flex justify-end">
        <a href="{{ route('admin.articles.create') }}" class="btn-earth">Tulis Artikel</a>
    </div>

    <div class="surface-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-[var(--sand)]/70 text-[var(--muted)]">
                    <tr>
                        <th class="px-6 py-3">Judul</th>
                        <th class="px-6 py-3">Author</th>
                        <th class="px-6 py-3">Publish</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($articles as $article)
                        <tr wire:key="article-{{ $article->id }}" class="border-t border-[var(--line)]">
                            <td class="px-6 py-4 font-semibold text-[var(--coffee)]">{{ $article->title }}</td>
                            <td class="px-6 py-4">{{ $article->author->name }}</td>
                            <td class="px-6 py-4">
                                {{ $article->is_published ? optional($article->published_at)->translatedFormat('d M Y') : 'Draft' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn-ghost">Edit</a>
                                    <button wire:click="delete({{ $article->id }})"
                                        wire:confirm="Hapus artikel &quot;{{ $article->title }}&quot;?"
                                        wire:loading.attr="disabled" wire:target="delete({{ $article->id }})"
                                        class="btn-danger">
                                        <span wire:loading.remove
                                            wire:target="delete({{ $article->id }})">Hapus</span>
                                        <span wire:loading wire:target="delete({{ $article->id }})">...</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-[var(--muted)]">Belum ada artikel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $articles->links() }}</div>
</div>
