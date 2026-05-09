<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn(['qty', 'subtotal']);
        });
    }

    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->decimal('qty', 8, 2)->after('service_id');
            $table->decimal('subtotal', 12, 2)->after('qty');
        });
    }
};
