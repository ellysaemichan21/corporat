<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('is_corporate')->default(false)->after('tier_level');
            $table->foreignId('partner_id')->nullable()->constrained('partners')->nullOnDelete()->after('is_corporate');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropColumn(['is_corporate', 'partner_id']);
        });
    }
};
