<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Task;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateStatusTaskTest extends TestCase
{
    use RefreshDatabase;

    protected Role $managerRole;
    protected Role $staffRole;
    protected Status $todoStatus;
    protected Status $doingStatus;
    protected Status $doneStatus;

    protected function setUp(): void
    {
        parent::setUp();

        // Roles
        $this->managerRole = Role::factory()->create(['name' => 'Manager']);
        $this->staffRole   = Role::factory()->create(['name' => 'Staff']);

        // Status
        $this->todoStatus  = Status::factory()->create(['name' => 'To Do']);
        $this->doingStatus = Status::factory()->create(['name' => 'Doing']);
        $this->doneStatus  = Status::factory()->create(['name' => 'Done']);
    }

    public function test_creator_can_update_task_status_successfully()
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);
        $task = Task::factory()->create([
            'creator_id' => $manager->id,
            'assignee_id' => $manager->id,
            'status_id' => $this->todoStatus->id,
        ]);

        $payload = ['status_id' => $this->doingStatus->id];

        $response = $this->actingAs($manager)->patchJson("/api/tasks/{$task->id}/status", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'message' => 'Success update task status',
                'data' => [
                    'id' => $task->id,
                    'status_id' => $this->doingStatus->id,
                ],
            ]);
    }

    public function test_assignee_can_update_task_status_successfully()
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);
        $task = Task::factory()->create([
            'creator_id' => $manager->id,
            'assignee_id' => $manager->id,
            'status_id' => $this->todoStatus->id,
        ]);

        $payload = ['status_id' => $this->doingStatus->id];

        $response = $this->actingAs($manager)->patchJson("/api/tasks/{$task->id}/status", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'message' => 'Success update task status',
            ]);
    }

    public function test_cannot_update_task_status_invalid_transition()
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);

        $task = Task::factory()->create([
            'creator_id' => $manager->id,
            'assignee_id' => $manager->id,
            'status_id' => $this->doingStatus->id, // current status Doing
        ]);

        $payload = ['status_id' => $this->doingStatus->id]; // ❌ invalid: Doing → Doing

        $response = $this->actingAs($manager)->patchJson("/api/tasks/{$task->id}/status", $payload);

        $response->assertStatus(422)
            ->assertJson([
                'message' => "Status dari 'Doing' tidak dapat diubah menjadi 'Doing'.",
                'errors' => [
                    'status_id' => ["Status dari 'Doing' tidak dapat diubah menjadi 'Doing'."]
                ],
            ]);
    }

    public function test_cannot_update_nonexistent_task_status()
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);

        $payload = ['status_id' => $this->doingStatus->id];

        $response = $this->actingAs($manager)->patchJson("/api/tasks/9999/status", $payload);

        $response->assertStatus(404);
    }
}
