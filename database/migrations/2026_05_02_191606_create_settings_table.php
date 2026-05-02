<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->unsigned();
            $table->string('email', 191);
            $table->integer('currency_id')->nullable()->default(null);
            $table->string('CompanyName', 191);
            $table->string('CompanyPhone', 191);
            $table->string('CompanyAdress', 191);
            $table->string('logo', 191)->nullable()->default(null);
            $table->tinyInteger('is_invoice_footer')->default(0);
            $table->string('invoice_footer', 192)->nullable()->default(null);
            $table->string('footer', 192)->default('Stocky - Ultimate Inventory With POS');
            $table->string('developed_by', 192)->default('Stocky');
            $table->integer('client_id')->nullable()->default(null);
            $table->integer('warehouse_id')->nullable()->default(null);
            $table->string('default_language', 192)->default('en');
            $table->integer('sms_gateway')->nullable()->default(1);
            $table->tinyInteger('show_language')->default(1);
            $table->tinyInteger('quotation_with_stock')->default(1);

            // kolom untuk POS & WA
            $table->string('whatsapp_token', 255)->nullable()->default(null);
            $table->string('whatsapp_base_url', 255)->nullable()->default('https://api.fonnte.com');
            $table->tinyInteger('whatsapp_enabled')->default(0);
            $table->string('receipt_store_name', 191)->nullable()->default(null);
            $table->string('receipt_store_address', 191)->nullable()->default(null);
            $table->string('receipt_store_phone', 191)->nullable()->default(null);
            $table->string('receipt_footer', 255)->nullable()->default('Barang yang sudah dibeli tidak dapat dikembalikan.');

            $table->timestamp('created_at', 6)->nullable()->default(null);
            $table->timestamp('updated_at', 6)->nullable()->default(null);
            $table->timestamp('deleted_at')->nullable()->default(null);
        });

        // Seed 1 row default
        DB::table('settings')->insert([
            'email'                => 'admin@example.com',
            'currency_id'          => null,
            'CompanyName'          => 'Toko Saya',
            'CompanyPhone'         => '08123456789',
            'CompanyAdress'        => 'Jl. Contoh No. 1',
            'logo'                 => null,
            'is_invoice_footer'    => 0,
            'invoice_footer'       => null,
            'footer'               => 'BeEs - Management Inventory With POS',
            'developed_by'         => 'BeEs',
            'client_id'            => null,
            'warehouse_id'         => null,
            'default_language'     => 'en',
            'sms_gateway'          => 1,
            'show_language'        => 1,
            'quotation_with_stock' => 1,
            'whatsapp_token'       => null,
            'whatsapp_base_url'    => 'https://api.fonnte.com',
            'whatsapp_enabled'     => 0,
            'receipt_store_name'   => 'Toko Saya',
            'receipt_store_address'=> 'Jl. Contoh No. 1',
            'receipt_store_phone'  => '08123456789',
            'receipt_footer'       => 'Barang yang sudah dibeli tidak dapat dikembalikan.',
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};