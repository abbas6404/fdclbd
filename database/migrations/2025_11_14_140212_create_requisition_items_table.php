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
        Schema::create('requisition_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisition_id');
            $table->unsignedBigInteger('head_of_account_id'); // Expense head from head of accounts
            $table->text('description')->nullable();
            $table->string('unit')->nullable(); // Unit of measurement (e.g., kg, pcs, liters)
            $table->unsignedInteger('qty')->default(1);
            $table->enum('confirmation_status', ['pending', 'confirmed', 'rejected'])->default('pending'); // Item-wise confirmation status
            $table->json('change_history')->nullable(); // History of changes: [{"field": "unit", "old_value": "pcs", "new_value": "kg", "changed_by": 1, "changed_at": "2025-01-01 12:00:00"}, ...]
            
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('requisition_id')->references('id')->on('requisitions')->onDelete('cascade');
            $table->foreign('head_of_account_id')->references('id')->on('head_of_accounts')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_items');
    }
};
