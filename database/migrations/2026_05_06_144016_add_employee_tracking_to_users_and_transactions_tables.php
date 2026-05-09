<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable()->after('password');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('sorter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('washer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('presser_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('packer_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['sorter_id']);
            $table->dropForeign(['washer_id']);
            $table->dropForeign(['presser_id']);
            $table->dropForeign(['packer_id']);
            $table->dropColumn(['sorter_id', 'washer_id', 'presser_id', 'packer_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
