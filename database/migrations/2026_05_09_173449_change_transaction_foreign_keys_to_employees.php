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
            // Drop old foreign keys pointing to users
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['sorter_id']);
            $table->dropForeign(['washer_id']);
            $table->dropForeign(['presser_id']);
            $table->dropForeign(['packer_id']);

            // Re-add pointing to employees
            $table->foreign('driver_id')->references('id')->on('employees')->nullOnDelete();
            $table->foreign('sorter_id')->references('id')->on('employees')->nullOnDelete();
            $table->foreign('washer_id')->references('id')->on('employees')->nullOnDelete();
            $table->foreign('presser_id')->references('id')->on('employees')->nullOnDelete();
            $table->foreign('packer_id')->references('id')->on('employees')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['sorter_id']);
            $table->dropForeign(['washer_id']);
            $table->dropForeign(['presser_id']);
            $table->dropForeign(['packer_id']);

            $table->foreign('driver_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('sorter_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('washer_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('presser_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('packer_id')->references('id')->on('users')->nullOnDelete();
        });
    }
};
