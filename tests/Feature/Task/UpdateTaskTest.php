<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Task;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTaskTest extends TestCase
{
    use RefreshDatabase;

    protected Role $managerRole;
    protected Role $staffRole;
    protected Status $todoStatus;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup role dan status dasar
        $this->managerRole = Role::factory()->create(['name' => 'Manager']);
        $this->staffRole   = Role::factory()->create(['name' => 'Staff']);
        $this->todoStatus  = Status::factory()->create(['name' => 'To Do']);
    }

    public function test_manager_can_update_own_task_successfully()
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);
        $task = Task::factory()->create([
            'creator_id' => $manager->id,
            'status_id' => $this->todoStatus->id,
            'title' => 'Cuci AC lama',
            'description' => 'Deskripsi lama',
        ]);

        $this->actingAs($manager);

        $payload = [
            'title' => 'Cuci AC ruang kelas ssss',
            'description' => 'Cuci seluruh AC rai 10.',
            'assignee_id' => $manager->id,
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([]);
    }

    public function test_manager_cannot_update_task_if_not_found()
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);
        $this->actingAs($manager);

        $payload = [
            'title' => 'Judul Baru',
            'description' => 'Deskripsi baru',
            'assignee_id' => $manager->id,
        ];

        $response = $this->putJson("/api/tasks/999", $payload);

        $response->assertStatus(404)
            ->assertJson([]);
    }

    public function test_manager_cannot_assign_task_to_non_staff()
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);
        $otherManager = User::factory()->create(['role_id' => $this->managerRole->id]);

        $task = Task::factory()->create([
            'creator_id' => $manager->id,
            'status_id' => $this->todoStatus->id,
        ]);

        $this->actingAs($manager);

        $payload = [
            'title' => 'Judul Baru',
            'description' => 'Deskripsi baru',
            'assignee_id' => $otherManager->id, // ❌ bukan staff dari manager
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['assignee_id']);
    }

    public function test_staff_cannot_assign_task_to_other_staff()
    {
        $staff = User::factory()->create(['role_id' => $this->staffRole->id]);
        $otherStaff = User::factory()->create([
            'role_id' => $this->staffRole->id,
            'manager_id' => $staff->id,
        ]);

        $task = Task::factory()->create([
            'creator_id' => $staff->id,
            'status_id' => $this->todoStatus->id,
        ]);

        $this->actingAs($staff);

        $payload = [
            'title' => 'Update title',
            'description' => 'Update desc',
            'assignee_id' => $otherStaff->id, // ❌ staff tidak boleh assign ke staff lain
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['assignee_id']);
    }

    public function test_staff_can_update_own_task()
    {
        $staff = User::factory()->create(['role_id' => $this->staffRole->id]);
        $task = Task::factory()->create([
            'creator_id' => $staff->id,
            'assignee_id' => $staff->id,
            'status_id' => $this->todoStatus->id,
        ]);

        $this->actingAs($staff);

        $payload = [
            'title' => 'Perbaiki AC kelas',
            'description' => 'Ganti filter AC',
            'assignee_id' => $staff->id,
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([]);
    }
}
