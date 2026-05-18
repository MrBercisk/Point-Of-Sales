<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah field needs_preparation ke products
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('needs_preparation')->default(false)->after('not_for_selling');
        });

        // Tabel kitchen_orders — satu row per order yang punya item masak
        Schema::create('kitchen_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_class')->nullable();
            // pending = antri, processing = diproses, done = selesai
            $table->enum('status', ['pending', 'processing', 'done'])->default('pending');
            $table->json('items'); // snapshot item yang perlu dimasak
            $table->timestamp('called_at')->nullable();   // waktu mulai diproses
            $table->timestamp('done_at')->nullable();     // waktu selesai
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('needs_preparation');
        });
        Schema::dropIfExists('kitchen_orders');
    }
};