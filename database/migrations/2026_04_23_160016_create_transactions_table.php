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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
        $table->foreignId('manifest_id')->nullable()->constrained('manifests')->nullOnDelete();
        
        $table->string('invoice_code')->unique();
        $table->decimal('total_price', 12, 2)->default(0);
        
        // The High-Class Tier System
        $table->enum('tier_level', ['Essential', 'Signature', 'Bespoke'])->default('Essential');
        
        // The Automated State Machine Statuses
        $table->enum('laundry_status', [
            'Pending', 
            'Sorting & QC', 
            'Washing', 
            'Drying', 
            'Ironing', 
            'Ready for Dispatch', 
            'Completed'
        ])->default('Pending');
        
        $table->enum('payment_status', ['Unpaid', 'Paid'])->default('Unpaid');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
