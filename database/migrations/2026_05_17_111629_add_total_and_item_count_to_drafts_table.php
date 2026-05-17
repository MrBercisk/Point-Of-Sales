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
        Schema::table('drafts', function (Blueprint $table) {
            $table->decimal('total', 12, 2)->default(0)->after('payment_method');
            $table->integer('item_count')->default(0)->after('total');
        });
    }

    public function down(): void
    {
        Schema::table('drafts', function (Blueprint $table) {
            $table->dropColumn(['total', 'item_count']);
        });
    }
};
