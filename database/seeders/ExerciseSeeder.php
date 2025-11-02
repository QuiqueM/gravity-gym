<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Exercise;
use App\Models\Category;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cardioCategory = Category::where('name', 'Cardio')->first();

        Exercise::create([
            'name' => 'Burpees',
            'description' => 'Ejercicio de cuerpo completo que combina una sentadilla, un salto y una flexión.',
            'demonstration_video' => null, // Puedes añadir un enlace de video de demostración aquí si lo tienes
            'category_id' => $cardioCategory->id,
        ]);
    }
}
