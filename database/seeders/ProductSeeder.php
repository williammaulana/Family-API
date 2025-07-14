<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $makanan = Category::where('name', 'Makanan')->first();
        $minuman = Category::where('name', 'Minuman')->first();
        $kebersihan = Category::where('name', 'Kebersihan')->first();
        $kesehatan = Category::where('name', 'Kesehatan')->first();

        $products = [
            // Makanan
            [
                'name' => 'Indomie Goreng',
                'category_id' => $makanan->id,
                'barcode' => '8992388101015',
                'buy_price' => 2500,
                'sell_price' => 3000,
                'stock' => 100,
                'min_stock' => 20,
                'unit' => 'pcs',
                'description' => 'Mie instan rasa ayam bawang',
            ],
            [
                'name' => 'Biscuit Roma Kelapa',
                'category_id' => $makanan->id,
                'barcode' => '8992775001011',
                'buy_price' => 8000,
                'sell_price' => 10000,
                'stock' => 50,
                'min_stock' => 10,
                'unit' => 'pcs',
                'description' => 'Biskuit rasa kelapa',
            ],
            [
                'name' => 'Chitato Sapi Panggang',
                'category_id' => $makanan->id,
                'barcode' => '8992388102012',
                'buy_price' => 12000,
                'sell_price' => 15000,
                'stock' => 30,
                'min_stock' => 5,
                'unit' => 'pcs',
                'description' => 'Keripik kentang rasa sapi panggang',
            ],

            // Minuman
            [
                'name' => 'Aqua 600ml',
                'category_id' => $minuman->id,
                'barcode' => '8992775622018',
                'buy_price' => 3000,
                'sell_price' => 4000,
                'stock' => 200,
                'min_stock' => 50,
                'unit' => 'pcs',
                'description' => 'Air mineral dalam kemasan',
            ],
            [
                'name' => 'Teh Botol Sosro',
                'category_id' => $minuman->id,
                'barcode' => '8992388103013',
                'buy_price' => 4500,
                'sell_price' => 6000,
                'stock' => 80,
                'min_stock' => 15,
                'unit' => 'pcs',
                'description' => 'Teh manis dalam botol',
            ],
            [
                'name' => 'Susu Ultra Coklat',
                'category_id' => $minuman->id,
                'barcode' => '8992775623019',
                'buy_price' => 12000,
                'sell_price' => 15000,
                'stock' => 60,
                'min_stock' => 20,
                'unit' => 'pcs',
                'description' => 'Susu UHT rasa coklat',
            ],
            [
                'name' => 'Kopi Kapal Api',
                'category_id' => $minuman->id,
                'barcode' => '8992388104014',
                'buy_price' => 4000,
                'sell_price' => 5000,
                'stock' => 40,
                'min_stock' => 10,
                'unit' => 'pcs',
                'description' => 'Kopi bubuk sachet',
            ],

            // Kebersihan
            [
                'name' => 'Sabun Mandi Lifebuoy',
                'category_id' => $kebersihan->id,
                'barcode' => '8992775624020',
                'buy_price' => 6000,
                'sell_price' => 8000,
                'stock' => 25,
                'min_stock' => 5,
                'unit' => 'pcs',
                'description' => 'Sabun mandi batang',
            ],
            [
                'name' => 'Shampo Pantene 170ml',
                'category_id' => $kebersihan->id,
                'barcode' => '8992388105015',
                'buy_price' => 15000,
                'sell_price' => 18000,
                'stock' => 20,
                'min_stock' => 5,
                'unit' => 'pcs',
                'description' => 'Shampo perawatan rambut',
            ],
            [
                'name' => 'Pasta Gigi Pepsodent',
                'category_id' => $kebersihan->id,
                'barcode' => '8992775625021',
                'buy_price' => 8000,
                'sell_price' => 10000,
                'stock' => 35,
                'min_stock' => 8,
                'unit' => 'pcs',
                'description' => 'Pasta gigi untuk gigi sehat',
            ],

            // Kesehatan
            [
                'name' => 'Panadol Extra',
                'category_id' => $kesehatan->id,
                'barcode' => '8992388106016',
                'buy_price' => 8000,
                'sell_price' => 12000,
                'stock' => 15,
                'min_stock' => 3,
                'unit' => 'strip',
                'description' => 'Obat sakit kepala dan demam',
            ],
            [
                'name' => 'Betadine 15ml',
                'category_id' => $kesehatan->id,
                'barcode' => '8992775626022',
                'buy_price' => 12000,
                'sell_price' => 15000,
                'stock' => 10,
                'min_stock' => 2,
                'unit' => 'pcs',
                'description' => 'Antiseptik untuk luka',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}