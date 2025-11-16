<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin kullanıcı
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Normal kullanıcı
        User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);

        // Kategoriler
        $category1 = Category::create([
            'name' => 'Elektronik',
            'description' => 'Elektronik ürünler kategorisi',
        ]);

        $category2 = Category::create([
            'name' => 'Giyim',
            'description' => 'Giyim ve moda ürünleri',
        ]);

        $category3 = Category::create([
            'name' => 'Ev & Yaşam',
            'description' => 'Ev ve yaşam ürünleri',
        ]);

        // Elektronik kategorisi ürünleri
        $products1 = [
            ['name' => 'iPhone 15 Pro', 'description' => 'Apple iPhone 15 Pro 256GB', 'price' => 45000.00, 'stock_quantity' => 50],
            ['name' => 'Samsung Galaxy S24', 'description' => 'Samsung Galaxy S24 Ultra 512GB', 'price' => 40000.00, 'stock_quantity' => 30],
            ['name' => 'MacBook Pro', 'description' => 'Apple MacBook Pro 14" M3', 'price' => 55000.00, 'stock_quantity' => 20],
            ['name' => 'AirPods Pro', 'description' => 'Apple AirPods Pro 2. Nesil', 'price' => 8500.00, 'stock_quantity' => 100],
            ['name' => 'iPad Air', 'description' => 'Apple iPad Air 11" M2', 'price' => 25000.00, 'stock_quantity' => 40],
        ];

        foreach ($products1 as $product) {
            Product::create(array_merge($product, ['category_id' => $category1->id]));
        }

        // Giyim kategorisi ürünleri
        $products2 = [
            ['name' => 'Erkek Tişört', 'description' => 'Pamuklu erkek tişört', 'price' => 150.00, 'stock_quantity' => 200],
            ['name' => 'Kadın Elbise', 'description' => 'Yazlık kadın elbise', 'price' => 350.00, 'stock_quantity' => 150],
            ['name' => 'Spor Ayakkabı', 'description' => 'Nike spor ayakkabı', 'price' => 2500.00, 'stock_quantity' => 80],
            ['name' => 'Jeans Pantolon', 'description' => 'Klasik fit jeans', 'price' => 450.00, 'stock_quantity' => 120],
            ['name' => 'Kazak', 'description' => 'Yünlü kazak', 'price' => 280.00, 'stock_quantity' => 90],
        ];

        foreach ($products2 as $product) {
            Product::create(array_merge($product, ['category_id' => $category2->id]));
        }

        // Ev & Yaşam kategorisi ürünleri
        $products3 = [
            ['name' => 'Kahve Makinesi', 'description' => 'Otomatik espresso makinesi', 'price' => 3500.00, 'stock_quantity' => 25],
            ['name' => 'Yatak Örtüsü', 'description' => 'Pamuklu yatak örtüsü takımı', 'price' => 450.00, 'stock_quantity' => 60],
            ['name' => 'Masa Lambası', 'description' => 'LED masa lambası', 'price' => 180.00, 'stock_quantity' => 100],
            ['name' => 'Mutfak Robotu', 'description' => 'Çok fonksiyonlu mutfak robotu', 'price' => 2800.00, 'stock_quantity' => 35],
            ['name' => 'Halı', 'description' => 'Yün halı 200x300 cm', 'price' => 1500.00, 'stock_quantity' => 15],
        ];

        foreach ($products3 as $product) {
            Product::create(array_merge($product, ['category_id' => $category3->id]));
        }
    }
}
