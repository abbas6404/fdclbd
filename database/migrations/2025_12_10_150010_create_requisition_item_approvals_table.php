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
        Schema::create('requisition_item_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_item_id')->constrained('requisition_items')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('approval_level_id')->nullable()->constrained('approval_levels')->onDelete('set null');
            $table->dateTime('approval_date');
            $table->enum('approval_status', ['approved', 'rejected'])->default('approved');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_item_approvals');
    }
};

