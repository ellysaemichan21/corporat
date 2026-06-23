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
    Schema::create('transaction_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Which worker did it
        
        $table->string('status_from')->nullable(); // e.g., 'Washing'
        $table->string('status_to');               // e.g., 'Ironing'
        $table->string('action_taken');            // e.g., "Worker finished wash cycle"
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_logs');
    }
};
