<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promotion;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promotion::create([
            'name' => 'Promoción de Apertura',
            'description' => 'Descuento del 20% en todas las membresías por el primer mes.',
            'is_active' => true,
            'image' => null,
        ]);
    }
}
