<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Task;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetTaskDetailTest extends TestCase
{
    use RefreshDatabase;

    protected Role $managerRole;
    protected Role $staffRole;
    protected Status $doingStatus;

    protected function setUp(): void
    {
        parent::setUp();

        // Roles
        $this->managerRole = Role::factory()->create(['name' => 'Manager']);
        $this->staffRole   = Role::factory()->create(['name' => 'Staff']);

        // Status
        $this->doingStatus = Status::factory()->create(['name' => 'Doing']);
    }

    public function test_can_get_task_detail_successfully()
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);

        $task = Task::factory()->create([
            'title' => 'Cuci AC ruang kelas',
            'description' => 'Cuci seluruh AC ruang kelas, dari lantai 1 sampai 10.',
            'status_id' => $this->doingStatus->id,
            'creator_id' => $manager->id,
            'assignee_id' => $manager->id,
            'report' => 'Seluruh AC ruang kelas asdis mengisi freon.',
        ]);

        $response = $this->actingAs($manager)->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status_id' => $task->status_id,
                'status' => ['name' => $task->status->name],
                'creator_id' => $manager->id,
                'creator' => ['name' => $manager->name],
                'assignee_id' => $manager->id,
                'assignee' => ['name' => $manager->name],
                'report' => $task->report,
            ]);
    }

    public function test_getting_nonexistent_task_returns_404()
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);

        $response = $this->actingAs($manager)->getJson("/api/tasks/9999");

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'Fail',
                'message' => 'Task not found',
                'data' => null,
            ]);
    }
}
