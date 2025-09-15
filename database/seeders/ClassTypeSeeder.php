<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassType;

class ClassTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Yoga', 'description' => 'Clase de yoga para todos los niveles'],
            ['name' => 'Spinning', 'description' => 'Clase de spinning de alta energía'],
            ['name' => 'Pilates', 'description' => 'Clase de pilates para fortalecer el core'],
        ];
        foreach ($types as $type) {
            ClassType::create($type);
        }
    }
}
