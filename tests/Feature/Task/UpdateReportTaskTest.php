<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Task;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateReportTaskTest extends TestCase
{
    use RefreshDatabase;

    protected Role $managerRole;
    protected Role $staffRole;
    protected Status $todoStatus;
    protected Status $doingStatus;

    protected function setUp(): void
    {
        parent::setUp();

        // Roles
        $this->managerRole = Role::factory()->create(['name' => 'Manager']);
        $this->staffRole   = Role::factory()->create(['name' => 'Staff']);

        // Status
        $this->todoStatus  = Status::factory()->create(['name' => 'To Do']);
        $this->doingStatus = Status::factory()->create(['name' => 'Doing']);
    }

    public function test_creator_can_update_report_successfully()
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);

        $task = Task::factory()->create([
            'creator_id' => $manager->id,
            'assignee_id' => $manager->id,
            'status_id' => $this->doingStatus->id,
        ]);

        $payload = ['report' => 'Seluruh AC ruang kelas asdis mengisi freon.'];

        $response = $this->actingAs($manager)->patchJson("/api/tasks/{$task->id}/report", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'message' => 'Success update task report',
                'data' => [
                    'id' => $task->id,
                    'report' => $payload['report'],
                ],
            ]);
    }


    public function test_assignee_can_update_report_successfully()
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);

        $task = Task::factory()->create([
            'creator_id' => $manager->id,
            'assignee_id' => $manager->id,
            'status_id' => $this->doingStatus->id,
        ]);

        $payload = ['report' => 'Report updated by assignee'];

        $response = $this->actingAs($manager)->patchJson("/api/tasks/{$task->id}/report", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'message' => 'Success update task report',
            ]);
    }

    public function test_cannot_update_report_if_status_not_doing()
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);

        $task = Task::factory()->create([
            'creator_id' => $manager->id,
            'assignee_id' => $manager->id,
            'status_id' => $this->todoStatus->id, // ❌ status bukan Doing
        ]);

        $payload = ['report' => 'Trying to update report'];

        $response = $this->actingAs($manager)->patchJson("/api/tasks/{$task->id}/report", $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status'])
            ->assertJson([
                'message' => 'Report hanya dapat diisi saat status Doing.',
            ]);
    }


    public function test_cannot_update_report_if_user_not_creator_or_assignee()
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);
        $otherUser = User::factory()->create(['role_id' => $this->staffRole->id]);

        $task = Task::factory()->create([
            'creator_id' => $manager->id,
            'assignee_id' => $manager->id,
            'status_id' => $this->doingStatus->id,
        ]);

        $payload = ['report' => 'Trying to update report'];

        $response = $this->actingAs($otherUser)->patchJson("/api/tasks/{$task->id}/report", $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['permission'])
            ->assertJson([
                'message' => 'Hanya pembuat atau pelaksana yang dapat mengisi report.',
            ]);
    }
}
