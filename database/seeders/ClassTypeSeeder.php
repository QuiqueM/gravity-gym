<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassType;

class ClassTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Calistenia', 'description' => 'Clase de calistenia para todos los niveles'],
        ];
        foreach ($types as $type) {
            ClassType::create($type);
        }
    }
}
