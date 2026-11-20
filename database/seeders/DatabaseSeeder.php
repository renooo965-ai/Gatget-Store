<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Employee;
use App\Models\Purchase;
use App\Models\SalesTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]
        );

        $categories = [
            ['name' => 'Smartphone', 'description' => 'Smartphone dan ponsel pintar', 'status' => 'active'],
            ['name' => 'Laptop', 'description' => 'Laptop dan komputer portable', 'status' => 'active'],
            ['name' => 'Accessories', 'description' => 'Aksesoris gadget pendukung', 'status' => 'active'],
            ['name' => 'Tablet', 'description' => 'Tablet dan perangkat mobile', 'status' => 'active'],
            ['name' => 'Headset', 'description' => 'Headset dan audio personal', 'status' => 'active'],
            ['name' => 'Gamepad', 'description' => 'Gamepad dan controller gaming', 'status' => 'inactive'],
            ['name' => 'Smartwatch', 'description' => 'Smartwatch dan wearable', 'status' => 'active'],
        ];

        foreach ($categories as $category) {
            Category::query()->firstOrCreate(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'status' => $category['status'],
                ]
            );
        }

        $categoryMap = Category::query()->pluck('id', 'name');
        $brands = ['Samsung', 'Apple', 'Xiaomi', 'Lenovo', 'Sony', 'Asus', 'Dell', 'Logitech', 'Realme', 'JBL'];
        $productNames = [
            'Galaxy A55', 'iPhone 15', 'Redmi Note 13', 'ThinkPad X1', 'Zenbook 14', 'AirPods Pro',
            'iPad Air', 'Tab S9', 'Headset Gaming Pro', 'Gamepad X', 'Smartwatch Neo', 'Laptop Aura',
            'Phone Mini', 'Tablet Lite', 'Gaming Headset', 'Controller V2', 'Smartband Pulse', 'Ultrabook 10'
        ];

        $productsPerCategory = [
            'Smartphone' => 42,
            'Laptop' => 36,
            'Accessories' => 32,
            'Tablet' => 28,
            'Headset' => 24,
            'Gamepad' => 21,
            'Smartwatch' => 17,
        ];
        $lowStockGoal = 20;
        $categorySequence = [];
        foreach ($productsPerCategory as $categoryName => $count) {
            for ($i = 0; $i < $count; $i++) {
                $categorySequence[] = $categoryName;
            }
        }

        for ($i = 1; $i <= count($categorySequence); $i++) {
            $categoryName = $categorySequence[$i - 1];
            $categoryId = $categoryMap[$categoryName];
            $brand = $brands[$i % count($brands)];
            $name = $productNames[$i % count($productNames)] . ' ' . (($i % 9) + 1);

            if ($i <= $lowStockGoal) {
                $stock = 2;
            } elseif ($i <= 120) {
                $stock = 28;
            } else {
                $stock = 27;
            }

            Product::query()->updateOrCreate(
                ['product_name' => 'GD-' . str_pad($i, 3, '0', STR_PAD_LEFT)],
                [
                    'category_id' => $categoryId,
                    'product_name' => 'GD-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'name' => $name,
                    'brand' => $brand,
                    'price' => 1000000 + ($i * 250000),
                    'stock' => $stock,
                    'description' => 'Produk gadget terbaru untuk kebutuhan sehari-hari.',
                ]
            );
        }

        $employee = Employee::query()->firstOrCreate(
            ['email' => 'kasir@gadgetstore.test'],
            [
                'name' => 'Dewi Kasir',
                'phone' => '081234567890',
                'position' => 'Kasir',
                'status' => 'active',
                'hire_date' => '2026-01-10',
                'address' => 'Toko Gadget Store',
            ]
        );

        $firstProduct = Product::query()->orderBy('id')->first();
        if ($firstProduct) {
            Purchase::query()->firstOrCreate(
                ['purchase_number' => 'PO-DEMO-001'],
                [
                    'supplier_name' => 'PT Gadget Distributor',
                    'product_id' => $firstProduct->id,
                    'quantity' => 10,
                    'purchase_price' => 750000,
                    'total_price' => 7500000,
                    'purchased_at' => now()->subDays(5)->toDateString(),
                    'notes' => 'Pembelian stok awal contoh',
                ]
            );

            SalesTransaction::query()->firstOrCreate(
                ['invoice_number' => 'INV-DEMO-001'],
                [
                    'customer_name' => 'Pelanggan Demo',
                    'employee_id' => $employee->id,
                    'product_id' => $firstProduct->id,
                    'quantity' => 1,
                    'unit_price' => $firstProduct->price,
                    'total_price' => $firstProduct->price,
                    'payment_method' => 'cash',
                    'status' => 'completed',
                    'transaction_date' => now()->subDay()->toDateString(),
                ]
            );
        }
    }
}
