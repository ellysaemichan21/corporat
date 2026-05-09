<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('is_fast_track')->default(false)->after('payment_status');
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->decimal('weight', 8, 2)->default(1.0)->after('service_id');
            $table->decimal('unit_price', 12, 2)->default(0.00)->after('weight');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('is_fast_track');
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn(['weight', 'unit_price']);
        });
    }
};
