<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('delivery_method', ['collection', 'dropoff'])->default('collection')->after('laundry_status');
            $table->text('pickup_address')->nullable()->after('delivery_method');
            $table->dateTime('expected_datetime')->nullable()->after('pickup_address');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['delivery_method', 'pickup_address', 'expected_datetime']);
        });
    }
};
