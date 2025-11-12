<?php

namespace Database\Seeders;


use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Database\Seeder;
use Database\Factories\TaskFactory;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $managers = User::whereHas('role', function($query){
                            $query->where('name', 'Manager');
                        })
                        ->get();
        $staffs = User::whereHas('role', function($query){
                            $query->where('name', 'Staff');
                        })
                        ->get();

        foreach ($managers as $manager) {
            // manager membuat task utk dirinya sendiri 
            Task::factory()->createWithService([
                'creator'       => $manager,
                'assignee_id'   => $manager->id
            ]);

            // manager membuat task utk staff nya
            $staff = $staffs->where('manager_id', $manager->id)
                            ->first();
            if($staff){
                Task::factory()->createWithService([
                    'creator'       => $manager,
                    'assignee_id'   => $staff->id
                ]);
            }
        }

        // Staff membuat task untuk dirinya sendiri
        foreach ($staffs as $staff) {
            Task::factory()->createWithService([
                'creator'       => $staff,
                'assignee_id'   => $staff->id
            ]);
        }
    }
}
