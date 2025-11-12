<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::factory()->count(2)->make()->each(function($role) {
        Role::updateOrCreate(
            ['name' => $role->name],
            $role->getAttributes()
        );
    });

    }
}
