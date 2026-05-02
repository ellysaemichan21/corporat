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
    Schema::create('manifests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete(); // Which driver
        $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete(); // Which apartment
        
        $table->enum('status', ['Pending', 'En Route', 'Delivered'])->default('Pending');
        $table->timestamp('scheduled_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manifests');
    }
};
