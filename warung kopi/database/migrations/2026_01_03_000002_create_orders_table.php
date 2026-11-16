<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20)->unique();
            $table->foreignId('dining_table_id')->nullable()->constrained('dining_tables')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('order_type', 20)->default('dine_in');
            $table->string('status', 20)->default('menunggu')->index();
            $table->string('payment_status', 20)->default('belum_dibayar');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('notes', 255)->nullable();
            $table->dateTime('ordered_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
