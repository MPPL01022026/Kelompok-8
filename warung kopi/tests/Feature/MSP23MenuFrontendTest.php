<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class MSP23MenuFrontendTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'database.connections.sqlite.url' => null]);
        DB::purge('sqlite');
        Artisan::call('migrate', ['--database' => 'sqlite', '--force' => true]);
        $this->withoutVite();
    }

    public function test_page_requires_a_signed_in_active_cashier(): void
    {
        $this->get('/menu')->assertRedirect('/login');
        $this->actingAs(User::factory()->create(['role' => 'waiter', 'is_active' => true]))
            ->get('/menu')->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'kasir', 'is_active' => false]))
            ->get('/menu')->assertRedirect('/login');
    }

    public function test_page_uses_real_categories_and_existing_api_route(): void
    {
        DB::table('categories')->insert(['name' => 'Minuman', 'created_at' => now(), 'updated_at' => now()]);
        $this->actingAs(User::factory()->create(['role' => 'kasir', 'is_active' => true]))
            ->get('/menu')->assertOk()->assertSee('Kelola menu')
            ->assertSee('Minuman')->assertSee('/internal-api/menus')->assertSee('menu-form');
    }

    public function test_empty_category_state_is_clear_and_save_is_disabled(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'kasir', 'is_active' => true]))
            ->get('/menu')->assertOk()->assertSee('Belum ada kategori')
            ->assertSee('disabled');
    }

    public function test_dashboard_link_is_visible_only_for_cashier_and_logout_is_preserved(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'kasir', 'is_active' => true]))
            ->get('/dashboard')->assertOk()->assertSee('Kelola menu')->assertSee('Keluar');
        $this->actingAs(User::factory()->create(['role' => 'waiter', 'is_active' => true]))
            ->get('/dashboard')->assertOk()->assertDontSee('Kelola menu')->assertSee('Keluar');
    }
}
