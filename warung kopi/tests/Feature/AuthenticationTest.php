<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_login_form_with_csrf_token(): void
    {
        $this->get('/login')->assertOk()->assertSee('name="_token"', false)
            ->assertSee('name="username"', false)->assertSee('name="password"', false);
    }

    public function test_guest_is_redirected_from_dashboard_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_active_cashier_can_login_using_username_and_password(): void
    {
        $user = User::factory()->create([
            'username' => 'kasir01', 'password' => 'sandi-benar', 'is_active' => true,
        ]);

        $this->post('/login', ['username' => 'kasir01', 'password' => 'sandi-benar'])
            ->assertRedirect('/dashboard')->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);
        $this->get('/dashboard')->assertOk()->assertSee($user->name);
    }

    public function test_invalid_password_does_not_authenticate(): void
    {
        User::factory()->create(['username' => 'kasir01', 'password' => 'sandi-benar']);

        $this->from('/login')->post('/login', ['username' => 'kasir01', 'password' => 'keliru'])
            ->assertRedirect('/login')->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_nonexistent_user_and_inactive_account_cannot_login(): void
    {
        User::factory()->create(['username' => 'nonaktif', 'password' => 'sandi-benar', 'is_active' => false]);
        $this->from('/login')->post('/login', ['username' => 'nonaktif', 'password' => 'sandi-benar'])
            ->assertSessionHasErrors('username');
        $this->assertGuest();
        $this->from('/login')->post('/login', ['username' => 'tidak-ada', 'password' => 'sandi-benar'])
            ->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_username_and_password_are_required(): void
    {
        $this->from('/login')->post('/login', [])->assertSessionHasErrors(['username', 'password']);
        $this->assertGuest();
    }

    public function test_user_is_locked_out_temporarily_after_five_failed_attempts(): void
    {
        User::factory()->create(['username' => 'kasir01', 'password' => 'sandi-benar']);
        for ($i = 0; $i < 5; $i++) {
            $this->from('/login')->post('/login', ['username' => 'kasir01', 'password' => 'salah'])
                ->assertSessionHasErrors('username');
        }
        $this->from('/login')->post('/login', ['username' => 'kasir01', 'password' => 'sandi-benar'])
            ->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_logout_clears_authentication_and_protects_dashboard(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_deactivated_user_is_logged_out_on_next_protected_request(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $this->actingAs($user);
        $user->update(['is_active' => false]);
        $this->get('/dashboard')->assertRedirect(route('login'))->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_authenticated_user_does_not_see_login_page(): void
    {
        $this->actingAs(User::factory()->create())->get('/login')->assertRedirect('/dashboard');
    }

    public function test_seeder_creates_a_hashed_password_and_does_not_overwrite_existing_account(): void
    {
        config(['cashier.username' => 'kasir-awal', 'cashier.password' => 'aman-12345', 'cashier.name' => 'Kasir Awal']);
        $this->seed(UserSeeder::class);
        $user = User::where('username', 'kasir-awal')->firstOrFail();
        $this->assertSame('kasir', $user->role);
        $this->assertTrue($user->is_active);
        $this->assertTrue(Hash::check('aman-12345', $user->password));
        config(['cashier.password' => 'sandi-berbeda', 'cashier.name' => 'Nama Lain']);
        $this->seed(UserSeeder::class);
        $user->refresh();
        $this->assertSame('Kasir Awal', $user->name);
        $this->assertTrue(Hash::check('aman-12345', $user->password));
        $this->assertSame(1, User::where('username', 'kasir-awal')->count());
    }

    public function test_seeder_refuses_missing_or_weak_password(): void
    {
        config(['cashier.username' => 'kasir-awal', 'cashier.password' => 'pendek', 'cashier.name' => 'Kasir']);
        $this->expectException(InvalidArgumentException::class);
        $this->seed(UserSeeder::class);
    }
}
