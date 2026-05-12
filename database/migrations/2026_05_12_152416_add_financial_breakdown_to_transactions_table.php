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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('promo_id')->nullable()->constrained('promos')->nullOnDelete()->after('total_price');
            $table->decimal('subtotal', 15, 2)->default(0)->after('total_price');
            $table->decimal('asap_surcharge', 15, 2)->default(0)->after('subtotal');
            $table->decimal('promo_discount', 15, 2)->default(0)->after('asap_surcharge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['promo_id']);
            $table->dropColumn(['promo_id', 'subtotal', 'asap_surcharge', 'promo_discount']);
        });
    }
};
