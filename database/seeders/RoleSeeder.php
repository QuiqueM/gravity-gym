<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Admin',
            'Coach',
            'Member',
        ];

        foreach ($roles as $roleName) {
          Role::firstOrCreate(['name' => $roleName]);
        }
    }
}
