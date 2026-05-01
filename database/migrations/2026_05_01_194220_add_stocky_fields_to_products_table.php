<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
       public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Barcode
            $table->string('barcode_symbology')->default('Code 128')->after('slug');
            $table->string('barcode')->nullable()->unique()->after('barcode_symbology');
 
            // Brand (relasi ke tabel brands jika ada, atau string saja)
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete()->after('category_id');
 
            // Tax
            $table->decimal('order_tax', 5, 2)->default(0)->after('price'); // dalam persen
            $table->enum('tax_type', ['Inclusive', 'Exclusive'])->default('Exclusive')->after('order_tax');
 
            // Cost & Unit
            $table->decimal('cost', 15, 2)->default(0)->after('price');
            $table->foreignId('product_unit_id')->nullable()->constrained('units')->nullOnDelete()->after('cost');
            $table->foreignId('sale_unit_id')->nullable()->constrained('units')->nullOnDelete()->after('product_unit_id');
            $table->foreignId('purchase_unit_id')->nullable()->constrained('units')->nullOnDelete()->after('sale_unit_id');
 
            // Product Type
            $table->enum('type', ['Standard Product', 'Service', 'Digital'])->default('Standard Product')->after('is_active');
 
            // Stock Alert
            $table->integer('stock_alert')->default(0)->after('stock');
 
            // Checkboxes
            $table->boolean('has_imei_serial')->default(false)->after('type');
            $table->boolean('not_for_selling')->default(false)->after('has_imei_serial');
        });
    }
 
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['product_unit_id']);
            $table->dropForeign(['sale_unit_id']);
            $table->dropForeign(['purchase_unit_id']);
 
            $table->dropColumn([
                'barcode_symbology',
                'barcode',
                'brand_id',
                'order_tax',
                'tax_type',
                'cost',
                'product_unit_id',
                'sale_unit_id',
                'purchase_unit_id',
                'type',
                'stock_alert',
                'has_imei_serial',
                'not_for_selling',
            ]);
        });
    }

};
