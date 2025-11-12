<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Services\TaskService;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): void
    {
        //
    }

    public function createWithService(array $data = [])
    {
        $service = app(TaskService::class);
        $creator = $data['creator'];

        return $service->createTask([
            'title' => $data['title'] ?? fake()->sentence(3),
            'description' => $data['description'] ?? fake()->paragraph(),
            'assignee_id' => $data['assignee_id'] ?? null,
        ], $creator);
    }
}
