<?php

namespace Tests\Feature\Task;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Status;
use App\Models\User;
use App\Models\Role;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    use RefreshDatabase;

    protected Role $managerRole;
    protected Role $staffRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->managerRole = Role::factory()->create(['name' => 'Manager']);
        $this->staffRole = Role::factory()->create(['name' => 'Staff']);

        Status::factory()->create(['name' => 'To Do']);
    }

    public function test_manager_can_create_task_for_themselves(): void
    {
        $manager = User::factory()->create([
            'role_id' => $this->managerRole->id,
        ]);

        $payload = [
            'title' => 'Cuci AC ruang kelas',
            'description' => 'Cuci seluruh AC ruang kelas, dari lantai 1 sampai 10.',
        ];

        $response = $this->actingAs($manager)->postJson('/api/tasks', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'Success',
                     'message' => 'Success Create Task',
                 ])
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'title',
                         'description',
                         'status_id',
                         'creator_id',
                         'assignee_id',
                         'report',
                         'created_at',
                         'updated_at'
                     ]
                 ]);

        $this->assertDatabaseHas('task', [
            'title' => $payload['title'],
            'creator_id' => $manager->id,
            'assignee_id' => $manager->id,
        ]);
    }

    public function test_manager_can_create_task_for_their_staff(): void
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);
        $staff = User::factory()->create([
            'role_id' => $this->staffRole->id,
            'manager_id' => $manager->id,
        ]);

        $payload = [
            'title' => 'Bersihkan lantai kantor',
            'description' => 'Bersihkan semua lantai kantor pagi ini.',
            'assignee_id' => $staff->id,
        ];

        $response = $this->actingAs($manager)->postJson('/api/tasks', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'Success',
                     'message' => 'Success Create Task',
                 ]);

        $this->assertDatabaseHas('task', [
            'title' => $payload['title'],
            'assignee_id' => $staff->id,
            'creator_id' => $manager->id,
        ]);
    }

    public function test_manager_cannot_assign_task_to_non_staff(): void
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);
        $otherManager = User::factory()->create(['role_id' => $this->managerRole->id]);

        $payload = [
            'title' => 'Cuci mobil',
            'description' => 'Cuci mobil perusahaan di parkiran.',
            'assignee_id' => $otherManager->id,
        ];

        $response = $this->actingAs($manager)->postJson('/api/tasks', $payload);

        $response->assertStatus(422)
                 ->assertJson([]);
    }

    public function test_staff_can_create_task_for_themselves(): void
    {
        $staff = User::factory()->create(['role_id' => $this->staffRole->id]);

        $payload = [
            'title' => 'Laporan harian',
            'description' => 'Membuat laporan harian aktivitas.',
        ];

        $response = $this->actingAs($staff)->postJson('/api/tasks', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'Success',
                     'message' => 'Success Create Task',
                 ]);

        $this->assertDatabaseHas('task', [
            'creator_id' => $staff->id,
            'assignee_id' => $staff->id,
        ]);
    }

    public function test_staff_cannot_assign_task_to_other_people(): void
    {
        $staff1 = User::factory()->create(['role_id' => $this->staffRole->id]);
        $staff2 = User::factory()->create(['role_id' => $this->staffRole->id]);

        $payload = [
            'title' => 'Bersihkan meja kerja',
            'description' => 'Bersihkan meja kerja tim A.',
            'assignee_id' => $staff2->id,
        ];

        $response = $this->actingAs($staff1)->postJson('/api/tasks', $payload);

        $response->assertStatus(422)
                 ->assertJson([]);
    }

    public function test_validation_fails_when_required_fields_missing(): void
    {
        $manager = User::factory()->create(['role_id' => $this->managerRole->id]);

        $response = $this->actingAs($manager)->postJson('/api/tasks', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'description']);
    }
}
