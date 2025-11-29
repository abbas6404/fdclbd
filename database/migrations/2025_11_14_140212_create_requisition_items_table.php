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
            $table->unsignedBigInteger('chart_of_account_id'); // Expense head from chart of accounts
            $table->text('description')->nullable();
            $table->decimal('qty', 10, 2)->default(1);
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('requisition_id')->references('id')->on('requisitions')->onDelete('cascade');
            $table->foreign('chart_of_account_id')->references('id')->on('head_of_accounts')->onDelete('restrict');
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
