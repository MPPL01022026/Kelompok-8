<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DiningTable;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class MSP26OrderFrontendTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'database.connections.sqlite.url' => null]);
        DB::purge('sqlite');
        Artisan::call('migrate', ['--database' => 'sqlite', '--force' => true]);
        $this->withoutVite();
    }

    public function test_order_page_requires_an_active_cashier(): void
    {
        $this->get('/pesanan/buat')->assertRedirect('/login');
        $this->actingAs(User::factory()->create(['role' => 'waiter', 'is_active' => true]))->get('/pesanan/buat')->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'kasir', 'is_active' => false]))->get('/pesanan/buat')->assertRedirect('/login');
    }

    public function test_cashier_can_open_form_with_existing_backend_routes_and_navigation(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'kasir', 'is_active' => true]));
        $this->get('/dashboard')->assertOk()->assertSee('Buat pesanan')->assertSee('Kelola menu')->assertSee('Keluar');
        $this->get('/pesanan/buat')->assertOk()->assertSee('/internal-api/orders/create')
            ->assertSee('/internal-api/orders')->assertSee('order-form')->assertSee('csrf-token')
            ->assertSee('Kelola menu')->assertSee('Keluar');
    }

    public function test_form_endpoints_provide_real_data_and_save_multiple_items_using_server_prices(): void
    {
        $category = Category::create(['name' => 'Kopi']);
        $coffee = Menu::create(['category_id' => $category->id, 'name' => 'Kopi', 'price' => '12500.50', 'is_available' => true]);
        $tea = Menu::create(['category_id' => $category->id, 'name' => 'Teh', 'price' => '7500.00', 'is_available' => true]);
        $table = DiningTable::create(['number' => '01', 'is_active' => true]);
        $this->actingAs(User::factory()->create(['role' => 'kasir', 'is_active' => true]));
        $this->getJson('/internal-api/orders/create')->assertOk()->assertJsonCount(2, 'data.menus')->assertJsonCount(1, 'data.dining_tables');
        $result = $this->postJson('/internal-api/orders', ['order_type' => 'dine_in', 'dining_table_id' => $table->id, 'items' => [
            ['menu_id' => $coffee->id, 'quantity' => 2], ['menu_id' => $tea->id, 'quantity' => 3],
        ]])->assertCreated()->assertJsonPath('data.status', 'menunggu')->assertJsonPath('data.total_amount', '47501.00');
        $order = Order::findOrFail($result->json('data.id'));
        $this->assertCount(2, $order->items);
        $this->assertSame($table->id, $order->dining_table_id);
    }
}
