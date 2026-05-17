<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // relasi ke siswa (nullable karena tamu tidak punya student)
            $table->foreignId('student_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('students')
                  ->nullOnDelete();

            // metode pembayaran: wallet atau cash
            $table->enum('payment_method', ['wallet', 'cash'])
                  ->default('cash')
                  ->after('status');

            // jumlah uang tunai yang dibayarkan (null kalau wallet)
            $table->decimal('cash_amount', 12, 2)
                  ->nullable()
                  ->after('payment_method');

            // kembalian (null kalau wallet)
            $table->decimal('change_amount', 12, 2)
                  ->nullable()
                  ->after('cash_amount');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropColumn(['student_id', 'payment_method', 'cash_amount', 'change_amount']);
        });
    }
};