<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $statuses = ['To Do','Doing','Done','Canceled'];
        foreach ($statuses as $name) {
            Status::updateOrCreate(['name' => $name], ['name' => $name]);
        }
    }
}
