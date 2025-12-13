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
        Schema::table('requisition_items', function (Blueprint $table) {
            $table->unsignedBigInteger('current_approval_level_id')->nullable()->after('confirmation_status');
            $table->integer('current_approval_sequence')->nullable()->after('current_approval_level_id');
        });

        // Add foreign key constraint after approval_levels table exists
        Schema::table('requisition_items', function (Blueprint $table) {
            $table->foreign('current_approval_level_id')->references('id')->on('approval_levels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisition_items', function (Blueprint $table) {
            $table->dropForeign(['current_approval_level_id']);
            $table->dropColumn(['current_approval_level_id', 'current_approval_sequence']);
        });
    }
};

