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
        Schema::table('requisition_approvals', function (Blueprint $table) {
            $table->foreignId('approval_level_id')->nullable()->after('requisition_id')->constrained('approval_levels')->onDelete('set null');
            $table->text('remarks')->nullable()->after('approval_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisition_approvals', function (Blueprint $table) {
            $table->dropForeign(['approval_level_id']);
            $table->dropColumn(['approval_level_id', 'remarks']);
        });
    }
};
