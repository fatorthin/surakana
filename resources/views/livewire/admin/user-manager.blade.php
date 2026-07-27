<div>
    {{-- Notification --}}
    @if ($notification)
        <div
            class="mb-4 rounded-2xl border px-4 py-3 text-sm font-medium
            {{ $notificationType === 'error'
                ? 'border-red-200 bg-red-50 text-red-800'
                : 'border-emerald-200 bg-emerald-50 text-emerald-800' }}">
            {{ $notification }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[0.85fr,1.15fr]">

        {{-- ── Form (add / edit) ── --}}
        <div class="surface-card space-y-5 p-6">
            <p class="eyebrow">
                {{ $editingId ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
            </p>

            <div>
                <x-input-label for="um-name" value="Nama Lengkap" />
                <x-text-input id="um-name" wire:model="name" class="mt-1 block w-full" placeholder="Budi Santoso" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="um-email" value="Alamat Email" />
                <x-text-input id="um-email" wire:model="email" type="email" class="mt-1 block w-full"
                    placeholder="budi@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="um-role" value="Role" />
                <select id="um-role" wire:model="role"
                    class="mt-1 block w-full rounded-[1.25rem] border-[var(--line)] bg-white text-sm focus:border-[var(--accent)] focus:ring-0">
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="um-password" :value="$editingId ? 'Password Baru (kosongkan jika tidak diubah)' : 'Password'" />
                <x-text-input id="um-password" wire:model="password" type="password" class="mt-1 block w-full"
                    placeholder="{{ $editingId ? '••••••••' : 'Min. 8 karakter' }}" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex gap-3 pt-1">
                <button wire:click="save" wire:loading.attr="disabled" wire:loading.class="opacity-60"
                    class="btn-earth flex-1 justify-center">
                    <span wire:loading.remove wire:target="save">
                        {{ $editingId ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
                    </span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>

                @if ($editingId)
                    <button wire:click="cancelEdit" class="btn-ghost">Batal</button>
                @endif
            </div>

            {{-- Summary stats --}}
            <div class="grid gap-3 border-t border-[var(--line)] pt-4 sm:grid-cols-3">
                <div class="mini-stat">
                    <span>Total</span>
                    <strong>{{ number_format($summary['total']) }}</strong>
                </div>
                <div class="mini-stat">
                    <span>Admin</span>
                    <strong>{{ number_format($summary['admin']) }}</strong>
                </div>
                <div class="mini-stat">
                    <span>Customer</span>
                    <strong>{{ number_format($summary['customer']) }}</strong>
                </div>
            </div>
        </div>

        {{-- ── Users table ── --}}
        <div class="surface-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-[var(--sand)]/70 text-[var(--muted)]">
                        <tr>
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Bergabung</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr wire:key="user-{{ $user->id }}"
                                class="border-t border-[var(--line)]
                                {{ $editingId === $user->id ? 'bg-[var(--accent)]/5' : '' }}">
                                <td class="px-6 py-4 font-medium">
                                    {{ $user->name }}
                                    @if ($user->id === auth()->id())
                                        <span
                                            class="ml-1 rounded-full bg-[var(--accent)]/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-widest text-[var(--accent-deep)]">Anda</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-[var(--muted)]">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="pill
                                        {{ $user->role === 'admin' ? 'bg-amber-100 text-amber-700' : 'bg-[var(--sand)] text-[var(--coffee)]' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-[var(--muted)]">
                                    {{ $user->created_at->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <button wire:click="edit({{ $user->id }})"
                                            class="btn-ghost py-1.5 px-3 text-xs">
                                            Edit
                                        </button>

                                        @if ($user->id !== auth()->id())
                                            <button wire:click="delete({{ $user->id }})"
                                                wire:confirm="Hapus pengguna {{ $user->name }}?"
                                                wire:loading.attr="disabled" wire:target="delete({{ $user->id }})"
                                                class="btn-danger py-1.5 px-3 text-xs">
                                                <span wire:loading.remove
                                                    wire:target="delete({{ $user->id }})">Hapus</span>
                                                <span wire:loading wire:target="delete({{ $user->id }})">...</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-[var(--muted)]">
                                    Belum ada pengguna.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">{{ $users->links() }}</div>
        </div>

    </div>
</div>
