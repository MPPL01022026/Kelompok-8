<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/** Uses a private in-memory SQLite database, never the developer's database. */
final class MSP22MenuBackendTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'database.connections.sqlite.url' => null]);
        DB::purge('sqlite');
        Artisan::call('migrate', ['--database' => 'sqlite', '--force' => true]);

        // Tests use the real MSP-21 migrations, not temporary menu/category fixtures.
        DB::table('categories')->insert(['name' => 'Kopi', 'created_at' => now(), 'updated_at' => now()]);
    }

    private function cashier(): User
    {
        return User::factory()->create(['role' => 'kasir', 'is_active' => true]);
    }

    private function menu(array $extra = []): int
    {
        return DB::table('menus')->insertGetId(array_merge([
            'category_id' => 1, 'name' => 'Kopi Hitam', 'price' => '10000.00',
            'is_available' => true, 'created_at' => now(), 'updated_at' => now(),
        ], $extra));
    }

    public function test_guest_cannot_access_menu_backend(): void
    {
        $this->getJson('/internal-api/menus')->assertUnauthorized();
    }

    public function test_inactive_user_cannot_access_menu_backend(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'kasir', 'is_active' => false]))
            ->get('/internal-api/menus')->assertRedirect('/login');
    }

    public function test_non_cashier_cannot_manage_menu(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'waiter', 'is_active' => true]))
            ->getJson('/internal-api/menus')->assertForbidden();
    }

    public function test_cashier_can_list_create_update_and_soft_delete_menu(): void
    {
        $this->actingAs($this->cashier());
        $id = $this->postJson('/internal-api/menus', ['name' => 'Latte', 'category_id' => 1, 'price' => '25000.00'])
            ->assertCreated()->assertJsonPath('data.name', 'Latte')->json('data.id');
        $this->getJson('/internal-api/menus')->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.category_name', 'Kopi');
        $this->putJson('/internal-api/menus/'.$id, ['name' => 'Latte Baru', 'category_id' => 1, 'price' => '27000'])
            ->assertOk()->assertJsonPath('data.name', 'Latte Baru');
        $this->deleteJson('/internal-api/menus/'.$id)->assertOk();
        $this->assertDatabaseHas('menus', ['id' => $id, 'name' => 'Latte Baru']);
        $this->assertNotNull(DB::table('menus')->where('id', $id)->value('deleted_at'));
        $this->getJson('/internal-api/menus')->assertOk()->assertJsonCount(0, 'data');
        $this->deleteJson('/internal-api/menus/'.$id)->assertNotFound();
    }

    public function test_soft_delete_keeps_menu_referenced_by_another_table(): void
    {
        Schema::create('menu_reference_fixture', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->restrictOnDelete();
        });
        $id = $this->menu();
        DB::table('menu_reference_fixture')->insert(['menu_id' => $id]);
        $this->actingAs($this->cashier())->deleteJson('/internal-api/menus/'.$id)->assertOk();
        $this->assertDatabaseHas('menu_reference_fixture', ['menu_id' => $id]);
        $this->assertDatabaseHas('menus', ['id' => $id]);
        $this->assertNotNull(DB::table('menus')->where('id', $id)->value('deleted_at'));
    }

    public function test_invalid_values_and_unknown_category_are_rejected(): void
    {
        $this->actingAs($this->cashier());
        $this->postJson('/internal-api/menus', ['name' => '', 'category_id' => 999, 'price' => 0])
            ->assertUnprocessable()->assertJsonValidationErrors(['name', 'category_id', 'price']);
        foreach (['-1', 'abc', '1.234', '10000000000'] as $price) {
            $this->postJson('/internal-api/menus', ['name' => 'Latte', 'category_id' => 1, 'price' => $price])
                ->assertUnprocessable()->assertJsonValidationErrors('price');
        }
        $this->assertSame(0, DB::table('menus')->count());
    }

    public function test_missing_or_deleted_menu_cannot_be_updated(): void
    {
        $this->actingAs($this->cashier());
        $id = $this->menu(['deleted_at' => now()]);
        $this->putJson('/internal-api/menus/'.$id, ['name' => 'Latte', 'category_id' => 1, 'price' => 10])->assertNotFound();
        $this->putJson('/internal-api/menus/999', ['name' => 'Latte', 'category_id' => 1, 'price' => 10])->assertNotFound();
    }
}
