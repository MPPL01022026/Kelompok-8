<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;

/** Optional sample menu only; not a source of actual business prices. */
class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        foreach ([
            ['Kopi', 'Kopi Hitam Contoh', '15000.00'],
            ['Kopi', 'Kopi Susu Contoh', '20000.00'],
            ['Non-Kopi', 'Teh Contoh', '10000.00'],
            ['Makanan', 'Roti Bakar Contoh', '18000.00'],
            ['Camilan', 'Kentang Goreng Contoh', '16000.00'],
        ] as [$categoryName, $name, $price]) {
            $category = Category::where('name', $categoryName)->firstOrFail();
            // Include deleted items to avoid recreating a deliberately deleted example.
            Menu::withTrashed()->firstOrCreate(
                ['category_id' => $category->id, 'name' => $name],
                ['price' => $price, 'is_available' => true],
            );
        }
    }
}
