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
            $table->string('customer_name')->nullable()->after('customer_id');
        });

        // Copy names from customers to transactions for historical integrity
        \Illuminate\Support\Facades\DB::table('transactions')
            ->join('customers', 'transactions.customer_id', '=', 'customers.id')
            ->update(['transactions.customer_name' => \Illuminate\Support\Facades\DB::raw('customers.name')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('customer_name');
        });
    }
};
