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
            $table->index(
                ['is_active', 'stock'],
                'products_active_stock_idx'
            );

            $table->index(
                ['category_id', 'is_active'],
                'products_category_active_idx'
            );

            $table->index(
                'barcode',
                'products_barcode_idx'
            ); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_is_active_stock_index');
            $table->dropIndex('products_category_id_is_active_index');
            $table->dropIndex('products_barcode_index');
        });
    }
};
