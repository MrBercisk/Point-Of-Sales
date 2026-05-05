<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // petugas yang input

            $table->enum('type', ['top_up', 'purchase', 'refund', 'adjustment']);
            // top_up    = petugas isi saldo
            // purchase  = belanja di kantin
            // refund    = pengembalian saldo
            // adjustment= koreksi saldo oleh admin

            $table->decimal('amount', 12, 2);          // nominal transaksi
            $table->decimal('balance_before', 12, 2);  // saldo sebelum
            $table->decimal('balance_after', 12, 2);   // saldo sesudah
            $table->string('reference')->nullable();    // nomor referensi / order id
            $table->text('note')->nullable();           // catatan tambahan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};