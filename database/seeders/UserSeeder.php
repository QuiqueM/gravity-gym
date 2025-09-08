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
        $coach = User::factory()->create([
            'name' => 'Coach User',
            'email' => 'coach@example.com',
            'phone' => '555-0002',
        ]);
        $member = User::factory()->create([
            'name' => 'Member User',
            'email' => 'member@example.com',
            'phone' => '555-0003',
        ]);

        $admin->roles()->sync([Role::where('name', 'Admin')->first()->id]);
        $coach->roles()->sync([Role::where('name', 'Coach')->first()->id]);
        $member->roles()->sync([Role::where('name', 'Member')->first()->id]);
    }
}
