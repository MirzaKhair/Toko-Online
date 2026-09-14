<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan', 'slug' => 'makanan', 'description' => 'Berbagai jenis makanan lezat'],
            ['name' => 'Minuman', 'slug' => 'minuman', 'description' => 'Berbagai jenis minuman segar'],
            ['name' => 'Pakaian', 'slug' => 'pakaian', 'description' => 'Berbagai jenis pakaian fashionable'],
            ['name' => 'Elektronik', 'slug' => 'elektronik', 'description' => 'Berbagai jenis elektronik terbaru'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $products = [
            // Makanan
            [
                'category_slug' => 'makanan',
                'name' => 'Nasi Goreng Spesial',
                'slug' => 'nasi-goreng-spesial',
                'description' => 'Nasi goreng dengan bumbu spesial, telur, dan ayam panggang.',
                'price' => 25000,
                'stock' => 50,
                'is_featured' => true,
            ],
            [
                'category_slug' => 'makanan',
                'name' => 'Mie Ayam Jamur',
                'slug' => 'mie-ayam-jamur',
                'description' => 'Mie ayam dengan topping jamur dan sayuran segar.',
                'price' => 22000,
                'stock' => 40,
                'is_featured' => true,
            ],
            [
                'category_slug' => 'makanan',
                'name' => 'Sate Ayam Madura',
                'slug' => 'sate-ayam-madura',
                'description' => 'Sate ayam bakar dengan bumbu kacang khas Madura.',
                'price' => 30000,
                'stock' => 30,
                'is_featured' => false,
            ],
            // Minuman
            [
                'category_slug' => 'minuman',
                'name' => 'Es Teh Manis',
                'slug' => 'es-teh-manis',
                'description' => 'Teh manis dingin yang menyegarkan.',
                'price' => 8000,
                'stock' => 100,
                'is_featured' => true,
            ],
            [
                'category_slug' => 'minuman',
                'name' => 'Jus Jeruk Segar',
                'slug' => 'jus-jeruk-segar',
                'description' => 'Jus jeruk murni tanpa pemanis buatan.',
                'price' => 15000,
                'stock' => 60,
                'is_featured' => true,
            ],
            [
                'category_slug' => 'minuman',
                'name' => 'Kopi Susu Latte',
                'slug' => 'kopi-susu-latte',
                'description' => 'Kopi robusta dengan susu segar dan gula aren.',
                'price' => 18000,
                'stock' => 50,
                'is_featured' => false,
            ],
            // Pakaian
            [
                'category_slug' => 'pakaian',
                'name' => 'Kaos Polos Katun',
                'slug' => 'kaos-polos-katun',
                'description' => 'Kaos polos 100% katun, nyaman dipakai sehari-hari.',
                'price' => 75000,
                'stock' => 100,
                'is_featured' => false,
            ],
            [
                'category_slug' => 'pakaian',
                'name' => 'Jaket Hoodie Premium',
                'slug' => 'jaket-hoodie-premium',
                'description' => 'Jaket hoodie dengan bahan premium, hangat dan stylish.',
                'price' => 185000,
                'stock' => 25,
                'is_featured' => false,
            ],
            // Elektronik
            [
                'category_slug' => 'elektronik',
                'name' => 'TWS Bluetooth 5.0',
                'slug' => 'tws-bluetooth-5',
                'description' => 'Earbuds wireless dengan bluetooth 5.0 dan noise cancelling.',
                'price' => 250000,
                'stock' => 30,
                'is_featured' => true,
            ],
            [
                'category_slug' => 'elektronik',
                'name' => 'Power Bank 10000mAh',
                'slug' => 'power-bank-10000mah',
                'description' => 'Power bank kapasitas besar dengan fast charging.',
                'price' => 175000,
                'stock' => 45,
                'is_featured' => true,
            ],
        ];

        foreach ($products as $product) {
            $category = Category::where('slug', $product['category_slug'])->first();
            if (!$category) continue;

            Product::firstOrCreate(
                ['slug' => $product['slug']],
                [
                    'category_id' => $category->id,
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'is_active' => true,
                    'is_featured' => $product['is_featured'],
                    'has_variants' => false,
                ]
            );
        }

        $this->command->info('Data contoh produk berhasil dibuat.');
    }
}
