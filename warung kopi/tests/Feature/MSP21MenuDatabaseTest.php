<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use Database\Seeders\MenuSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

final class MSP21MenuDatabaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Always isolated from real user data, even when local .env uses another DB.
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'database.connections.sqlite.url' => null]);
        DB::purge('sqlite');
        Artisan::call('migrate', ['--database' => 'sqlite', '--force' => true]);
    }

    public function test_real_migrations_create_categories_and_menus(): void
    {
        $this->assertTrue(Schema::hasTable('categories'));
        $this->assertTrue(Schema::hasTable('menus'));
        $this->assertTrue(Schema::hasColumns('menus', ['category_id', 'name', 'price', 'is_available', 'deleted_at']));
    }

    public function test_menu_and_category_models_are_related_and_soft_delete(): void
    {
        $category = Category::create(['name' => 'Kopi']);
        $menu = Menu::create(['category_id' => $category->id, 'name' => 'Latte', 'price' => '12500.50']);
        $this->assertSame('Kopi', $menu->category->name);
        $this->assertSame('12500.50', $menu->price);
        $this->assertSame(1, $category->menus()->count());
        $menu->delete();
        $this->assertNull(Menu::find($menu->id));
        $this->assertNotNull(Menu::withTrashed()->find($menu->id));
        $this->assertSame(0, $category->menus()->count());
    }

    public function test_seeder_can_be_repeated_without_overwriting_or_resurrecting_samples(): void
    {
        $this->seed(MenuSeeder::class);
        $this->assertSame(4, Category::count());
        $this->assertSame(5, Menu::count());
        $edited = Menu::where('name', 'Kopi Hitam Contoh')->firstOrFail();
        $edited->update(['price' => '12345.00']);
        $deleted = Menu::where('name', 'Kopi Susu Contoh')->firstOrFail();
        $deleted->delete();
        $this->seed(MenuSeeder::class);
        $this->assertSame(4, Category::count());
        $this->assertSame(5, Menu::withTrashed()->count());
        $this->assertSame('12345.00', $edited->fresh()->price);
        $this->assertTrue(Menu::withTrashed()->findOrFail($deleted->id)->trashed());
    }
}
