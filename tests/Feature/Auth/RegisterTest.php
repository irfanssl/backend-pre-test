<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully()
    {
        $managerRole = Role::factory()->create(['name' => 'Manager']);
        $staffRole = Role::factory()->create(['name' => 'Staff']);

        // Buat user manager agar bisa jadi manager_id
        $manager = User::factory()->create([
            'role_id' => $managerRole->id,
        ]);

        $payload = [
            'name' => 'Manager',
            'email' => 'manager@email.com',
            'password' => 'rahasia',
            'role_id' => $staffRole->id,
            'manager_id' => $manager->id,
        ];

        $response = $this->postJson('/api/users', $payload);

        $response->assertStatus(200)
            ->assertJson([]);

        $this->assertDatabaseHas('users', [
            'email' => 'manager@email.com',
            'role_id' => $staffRole->id,
            'manager_id' => $manager->id,
        ]);
    }

    public function test_it_fails_when_required_fields_are_missing()
    {
        $response = $this->postJson('/api/users', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'role_id', 'manager_id']);
    }

    public function test_it_fails_when_email_is_not_unique()
    {
        $role = Role::factory()->create(['name' => 'Manager']);
        $manager = User::factory()->create(['role_id' => $role->id]);

        User::factory()->create(['email' => 'duplicate@email.com']);

        $payload = [
            'name' => 'User Test',
            'email' => 'duplicate@email.com',
            'password' => 'secret123',
            'role_id' => $role->id,
            'manager_id' => $manager->id,
        ];

        $response = $this->postJson('/api/users', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_fails_when_manager_id_is_not_a_manager()
    {
        $roleStaff = Role::factory()->create(['name' => 'Staff']);
        $nonManager = User::factory()->create(['role_id' => $roleStaff->id]);

        $payload = [
            'name' => 'Invalid Manager Test',
            'email' => 'invalidmanager@example.com',
            'password' => 'secret123',
            'role_id' => $roleStaff->id,
            'manager_id' => $nonManager->id, // bukan manager
        ];

        $response = $this->postJson('/api/users', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['manager_id']);
    }

    public function test_it_fails_when_role_id_does_not_exist()
    {
        $managerRole = Role::factory()->create(['name' => 'Manager']);
        $manager = User::factory()->create(['role_id' => $managerRole->id]);

        $payload = [
            'name' => 'Invalid Role',
            'email' => 'invalidrole@example.com',
            'password' => 'secret123',
            'role_id' => 999, // role_id tidak ada
            'manager_id' => $manager->id,
        ];

        $response = $this->postJson('/api/users', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['role_id']);
    }
}
