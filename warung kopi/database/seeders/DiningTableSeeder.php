<?php

namespace Database\Seeders;

use App\Models\DiningTable;
use Illuminate\Database\Seeder;

/** Optional example tables; do not assume these match the actual venue. */
class DiningTableSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 10) as $number) {
            DiningTable::firstOrCreate(['number' => str_pad((string) $number, 2, '0', STR_PAD_LEFT)], ['is_active' => true]);
        }
    }
}
