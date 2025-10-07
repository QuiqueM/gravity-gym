<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '555-0001',
        ]);
        $admin->roles()->sync([Role::where('name', 'Admin')->first()->id]);

        // Crear 3 coach
        $coaches = User::factory(3)->create();
        foreach ($coaches as $coach) {
            $coach->roles()->sync([Role::where('name', 'Coach')->first()->id]);
        }

        // Crear 1 member de ejemplo
        $member = User::factory()->create([
            'name' => 'Member User',
            'email' => 'member@example.com',
            'phone' => '555-0003',
        ]);
        $member->roles()->sync([Role::where('name', 'Member')->first()->id]);

        // Crear 100 members adicionales
        // $members = User::factory(100)->create();
        // foreach ($members as $mem) {
        //     $mem->roles()->sync([Role::where('name', 'Member')->first()->id]);
        // }
    }
}
