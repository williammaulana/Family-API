<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Makanan',
                'description' => 'Produk makanan dan snack',
                'color' => '#10B981',
            ],
            [
                'name' => 'Minuman',
                'description' => 'Minuman segar dan kemasan',
                'color' => '#3B82F6',
            ],
            [
                'name' => 'Kebersihan',
                'description' => 'Produk kebersihan dan perawatan',
                'color' => '#8B5CF6',
            ],
            [
                'name' => 'Kesehatan',
                'description' => 'Produk kesehatan dan obat-obatan',
                'color' => '#EF4444',
            ],
            [
                'name' => 'Rumah Tangga',
                'description' => 'Peralatan dan kebutuhan rumah tangga',
                'color' => '#F59E0B',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}