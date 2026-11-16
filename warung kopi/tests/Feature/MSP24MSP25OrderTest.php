<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DiningTable;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\DiningTableSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/** Isolates every test in an in-memory SQLite database. */
final class MSP24MSP25OrderTest extends TestCase
{
    private Menu $coffee;
    private Menu $tea;
    private DiningTable $table;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'database.connections.sqlite.url' => null, 'database.connections.sqlite.foreign_key_constraints' => true]);
        DB::purge('sqlite');
        $this->assertSame(0, Artisan::call('migrate', ['--database' => 'sqlite', '--force' => true]));
        $cat = Category::create(['name' => 'Kopi']);
        $this->coffee = Menu::create(['category_id' => $cat->id, 'name' => 'Kopi', 'price' => '12500.50', 'is_available' => true]);
        $this->tea = Menu::create(['category_id' => $cat->id, 'name' => 'Teh', 'price' => '7500.00', 'is_available' => true]);
        $this->table = DiningTable::create(['number' => '01']);
    }

    private function cashier(): User
    {
        return User::factory()->create(['role' => 'kasir', 'is_active' => true]);
    }

    private function payload(): array
    {
        return ['order_type' => 'dine_in', 'dining_table_id' => $this->table->id, 'notes' => 'Sedikit gula', 'items' => [
            ['menu_id' => $this->coffee->id, 'quantity' => 2, 'notes' => 'Hangat'],
            ['menu_id' => $this->tea->id, 'quantity' => 3],
        ]];
    }

    public function test_migrations_and_models_create_expected_relations(): void
    {
        foreach (['dining_tables', 'orders', 'order_items'] as $table) $this->assertTrue(Schema::hasTable($table));
        $user = $this->cashier();
        $response = $this->actingAs($user)->postJson('/internal-api/orders', $this->payload())->assertCreated();
        $order = Order::findOrFail($response->json('data.id'));
        $this->assertSame($user->id, $order->creator->id);
        $this->assertSame('01', $order->diningTable->number);
        $this->assertCount(2, $order->items);
        $this->assertSame('Kopi', $order->items[0]->menu->name);
        $this->assertSame(1, $this->table->orders()->count());
    }

    public function test_valid_order_uses_server_prices_and_returns_snapshot(): void
    {
        $user = $this->cashier();
        $payload = $this->payload();
        $payload['items'][0]['unit_price'] = '1.00';
        $payload['total_amount'] = '1.00';
        $payload['created_by'] = 99999;
        $payload['status'] = 'selesai';
        $response = $this->actingAs($user)->postJson('/internal-api/orders', $payload)->assertCreated()
            ->assertJsonPath('data.status', 'menunggu')->assertJsonPath('data.payment_status', 'belum_dibayar')
            ->assertJsonPath('data.total_amount', '47501.00')->assertJsonPath('data.created_by', $user->id)
            ->assertJsonPath('data.items.0.unit_price', '12500.50')->assertJsonPath('data.items.0.subtotal', '25001.00')
            ->assertJsonPath('data.items.1.subtotal', '22500.00');
        $order = Order::findOrFail($response->json('data.id'));
        $this->assertStringStartsWith('ORD-', $order->order_number);
        $this->coffee->update(['price' => '20000']);
        $this->assertSame('12500.50', $order->items()->first()->unit_price);
    }

    public function test_monetary_calculation_does_not_use_floating_point(): void
    {
        $this->coffee->update(['price' => '0.10']);
        $this->tea->update(['price' => '0.20']);
        $this->actingAs($this->cashier())->postJson('/internal-api/orders', ['items' => [
            ['menu_id' => $this->coffee->id, 'quantity' => 3], ['menu_id' => $this->tea->id, 'quantity' => 1],
        ]])->assertCreated()->assertJsonPath('data.total_amount', '0.50');
    }

    public function test_create_data_lists_available_menus_and_active_tables_only(): void
    {
        $this->tea->update(['is_available' => false]);
        DiningTable::create(['number' => '02', 'is_active' => false]);
        $this->actingAs($this->cashier())->getJson('/internal-api/orders/create')
            ->assertOk()->assertJsonCount(1, 'data.menus')->assertJsonCount(1, 'data.dining_tables')
            ->assertJsonPath('data.menus.0.name', 'Kopi');
    }

    public function test_guest_and_non_cashier_cannot_access(): void
    {
        $this->postJson('/internal-api/orders', $this->payload())->assertUnauthorized();
        $this->actingAs(User::factory()->create(['role' => 'waiter', 'is_active' => true]))
            ->postJson('/internal-api/orders', $this->payload())->assertForbidden();
    }

    public function test_inactive_cashier_cannot_create_order(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'kasir', 'is_active' => false]))
            ->post('/internal-api/orders', $this->payload())->assertRedirect('/login');
        $this->assertSame(0, Order::count());
    }

    public function test_invalid_quantity_invalid_order_type_and_empty_items_are_rejected(): void
    {
        $this->actingAs($this->cashier());
        foreach ([0, -1, 1.5, 65536] as $qty) {
            $this->postJson('/internal-api/orders', ['items' => [['menu_id' => $this->coffee->id, 'quantity' => $qty]]])
                ->assertUnprocessable()->assertJsonValidationErrors('items.0.quantity');
        }
        $this->postJson('/internal-api/orders', ['items' => []])->assertUnprocessable()->assertJsonValidationErrors('items');
        $this->postJson('/internal-api/orders', ['order_type' => 'unknown', 'items' => [['menu_id' => $this->coffee->id, 'quantity' => 1]]])
            ->assertUnprocessable()->assertJsonValidationErrors('order_type');
        $this->assertSame(0, Order::count());
    }

    public function test_missing_unavailable_or_deleted_menus_are_rejected_atomically(): void
    {
        $this->actingAs($this->cashier());
        foreach ([999999, $this->tea->id] as $id) {
            $this->tea->update(['is_available' => false]);
            $payload = ['items' => [['menu_id' => $this->coffee->id, 'quantity' => 1], ['menu_id' => $id, 'quantity' => 1]]];
            $this->postJson('/internal-api/orders', $payload)->assertUnprocessable()->assertJsonValidationErrors('items.1.menu_id');
        }
        $this->tea->delete();
        $this->postJson('/internal-api/orders', ['items' => [['menu_id' => $this->tea->id, 'quantity' => 1]]])
            ->assertUnprocessable()->assertJsonValidationErrors('items.0.menu_id');
        $this->assertSame(0, DB::table('orders')->count());
        $this->assertSame(0, DB::table('order_items')->count());
    }

    public function test_missing_or_inactive_table_is_rejected_and_null_table_is_allowed(): void
    {
        $this->actingAs($this->cashier());
        $this->table->update(['is_active' => false]);
        foreach ([$this->table->id, 999] as $id) {
            $this->postJson('/internal-api/orders', ['dining_table_id' => $id, 'items' => [['menu_id' => $this->coffee->id, 'quantity' => 1]]])
                ->assertUnprocessable()->assertJsonValidationErrors('dining_table_id');
        }
        $this->postJson('/internal-api/orders', ['order_type' => 'take_away', 'items' => [['menu_id' => $this->coffee->id, 'quantity' => 1]]])
            ->assertCreated()->assertJsonPath('data.dining_table_id', null)->assertJsonPath('data.order_type', 'take_away');
    }

    public function test_ordered_menu_remains_readable_after_soft_delete(): void
    {
        $id = $this->actingAs($this->cashier())->postJson('/internal-api/orders', $this->payload())->assertCreated()->json('data.id');
        $this->coffee->delete();
        $this->assertSame('Kopi', Order::findOrFail($id)->items()->first()->menu->name);
    }

    public function test_huge_total_is_rejected_without_storing_order(): void
    {
        $this->coffee->update(['price' => '9999999999.99']);
        $this->actingAs($this->cashier())->postJson('/internal-api/orders', ['items' => [['menu_id' => $this->coffee->id, 'quantity' => 2]]])
            ->assertUnprocessable();
        $this->assertSame(0, Order::count());
    }

    public function test_database_transaction_rolls_back_if_saving_an_item_fails(): void
    {
        $user = $this->cashier();
        $attempt = 0;
        \App\Models\OrderItem::creating(function () use (&$attempt) {
            if (++$attempt === 2) {
                throw new \RuntimeException('Simulated item storage failure');
            }
        });
        try {
            app(\App\Services\OrderService::class)->create($this->payload(), $user);
            $this->fail('Expected a simulated error');
        } catch (\RuntimeException $e) {
            $this->assertSame('Simulated item storage failure', $e->getMessage());
        } finally {
            \App\Models\OrderItem::flushEventListeners();
        }
        $this->assertSame(0, DB::table('orders')->count());
        $this->assertSame(0, DB::table('order_items')->count());
    }

    public function test_optional_table_seeder_is_repeatable_without_overwriting_changes(): void
    {
        $this->seed(DiningTableSeeder::class);
        $this->table->update(['is_active' => false]);
        $this->seed(DiningTableSeeder::class);
        $this->assertSame(10, DiningTable::count());
        $this->assertFalse($this->table->fresh()->is_active);
    }
}
