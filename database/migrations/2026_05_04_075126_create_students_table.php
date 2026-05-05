<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nisn')->unique()->nullable();    // Nomor Induk Siswa Nasional
            $table->string('class');                         // contoh: 1A, 2B, 3C
            $table->enum('gender', ['L', 'P']);              // L = Laki, P = Perempuan
            $table->string('parent_name')->nullable();       // nama orang tua
            $table->string('parent_phone')->nullable();      // WA orang tua untuk notif
            $table->string('photo')->nullable();
            $table->string('barcode')->unique()->nullable(); // untuk scan kartu siswa
            $table->decimal('balance', 12, 2)->default(0);  // saldo dompet
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};