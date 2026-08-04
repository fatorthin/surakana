<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\UserManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_add_new_user_from_livewire_component(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->actingAs($admin);

        Livewire::test(UserManager::class)
            ->set('name', 'Budi Santoso')
            ->set('email', 'budi@example.com')
            ->set('role', User::ROLE_CUSTOMER)
            ->set('password', 'rahasia123')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role' => User::ROLE_CUSTOMER,
        ]);
    }
}
