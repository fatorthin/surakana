<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination;

    public string $name = '';
    public string $email = '';
    public string $role = User::ROLE_CUSTOMER;
    public string $password = '';
    public ?int $editingId = null;
    public string $notification = '';
    public string $notificationType = 'success';

    protected function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'role'     => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_CUSTOMER])],
            'password' => $this->editingId
                ? ['nullable', 'string', 'min:8']
                : ['required', 'string', 'min:8'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);

            $data = [
                'name'  => $validated['name'],
                'email' => $validated['email'],
                'role'  => $validated['role'],
            ];

            if (! empty($validated['password'])) {
                $data['password'] = $validated['password'];
            }

            $user->update($data);
            $this->notify('Data pengguna berhasil diperbarui.');
        } else {
            User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'role'     => $validated['role'],
                'password' => $validated['password'],
            ]);
            $this->notify('Pengguna baru berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function edit(User $user): void
    {
        $this->editingId = $user->id;
        $this->name      = $user->name;
        $this->email     = $user->email;
        $this->role      = $user->role;
        $this->password  = '';
        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    public function delete(User $user): void
    {
        if ($user->id === Auth::id()) {
            $this->notify('Tidak dapat menghapus akun Anda sendiri.', 'error');

            return;
        }

        $user->delete();
        $this->notify('Pengguna berhasil dihapus.');
    }

    protected function resetForm(): void
    {
        $this->reset(['name', 'email', 'password', 'editingId']);
        $this->role = User::ROLE_CUSTOMER;
    }

    protected function notify(string $message, string $type = 'success'): void
    {
        $this->notification     = $message;
        $this->notificationType = $type;
        $this->js('setTimeout(() => $wire.set("notification", ""), 3000)');
    }

    public function render(): View
    {
        $users = User::query()->latest()->paginate(12);

        $summary = [
            'total'    => User::query()->count(),
            'admin'    => User::query()->where('role', User::ROLE_ADMIN)->count(),
            'customer' => User::query()->where('role', User::ROLE_CUSTOMER)->count(),
        ];

        return view('livewire.admin.user-manager', compact('users', 'summary'));
    }
}
