<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('receipt_layout', 20)->default('standard')->after('receipt_footer');
            $table->boolean('receipt_show_logo')->default(false)->after('receipt_layout');
            $table->boolean('receipt_show_store_name')->default(true)->after('receipt_show_logo');
            $table->boolean('receipt_show_address')->default(true)->after('receipt_show_store_name');
            $table->boolean('receipt_show_phone')->default(true)->after('receipt_show_address');
            $table->boolean('receipt_show_invoice_number')->default(true)->after('receipt_show_phone');
            $table->boolean('receipt_show_date')->default(true)->after('receipt_show_invoice_number');
            $table->boolean('receipt_show_student')->default(true)->after('receipt_show_date');
            $table->boolean('receipt_show_payment_method')->default(true)->after('receipt_show_student');
            $table->boolean('receipt_show_item_price')->default(true)->after('receipt_show_payment_method');
            $table->boolean('receipt_show_subtotal_per_item')->default(true)->after('receipt_show_item_price');
            $table->boolean('receipt_show_cashier')->default(false)->after('receipt_show_subtotal_per_item');
            $table->boolean('receipt_show_change')->default(true)->after('receipt_show_cashier');
            $table->boolean('receipt_show_balance_after')->default(true)->after('receipt_show_change');
            $table->boolean('receipt_show_footer')->default(true)->after('receipt_show_balance_after');
            $table->boolean('receipt_show_barcode')->default(false)->after('receipt_show_footer');
            $table->string('receipt_paper_size', 10)->default('80mm')->after('receipt_show_barcode');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'receipt_layout',
                'receipt_show_logo', 'receipt_show_store_name', 'receipt_show_address',
                'receipt_show_phone', 'receipt_show_invoice_number', 'receipt_show_date',
                'receipt_show_student', 'receipt_show_payment_method', 'receipt_show_item_price',
                'receipt_show_subtotal_per_item', 'receipt_show_cashier', 'receipt_show_change',
                'receipt_show_balance_after', 'receipt_show_footer', 'receipt_show_barcode',
                'receipt_paper_size',
            ]);
        });
    }
};