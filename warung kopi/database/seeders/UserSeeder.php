<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use InvalidArgumentException;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $username = config('cashier.username');
        $password = config('cashier.password');
        $name = config('cashier.name', 'Kasir');

        if (! is_string($username) || trim($username) === '' || mb_strlen($username) > 50
            || ! is_string($password) || mb_strlen($password) < 8
            || ! is_string($name) || trim($name) === '') {
            throw new InvalidArgumentException(
                'Isi MR_COFFEE_CASHIER_USERNAME dan MR_COFFEE_CASHIER_PASSWORD (minimal 8 karakter) di .env; lihat panduan MSP-13.'
            );
        }

        // Do not overwrite an existing team member's account or password.
        $user = User::query()->firstOrCreate(
            ['username' => trim($username)],
            ['name' => trim($name), 'role' => 'kasir', 'is_active' => true, 'password' => $password],
        );

        if ($this->command) {
            $this->command->info($user->wasRecentlyCreated
                ? 'Akun kasir awal dibuat.'
                : 'Username sudah ada; akun dan kata sandinya tidak diubah.');
        }
    }
}
