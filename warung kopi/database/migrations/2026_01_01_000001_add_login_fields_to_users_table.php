<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Nullable keeps any users from the Laravel scaffold intact.
            // Users without a username cannot log in until assigned one.
            $table->string('username', 50)->nullable()->unique();
            $table->string('role', 20)->default('kasir');
            $table->boolean('is_active')->default(true);
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Never silently turn NULL emails into data or remove account details.
        if (DB::table('users')->whereNull('email')->exists()) {
            throw new RuntimeException('Rollback dibatalkan: ada pengguna tanpa email. Siapkan backup dan migrasi data dahulu.');
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['username', 'role', 'is_active']);
            $table->string('email')->nullable(false)->change();
        });
    }
};
