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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
            $table->unsignedBigInteger('head_of_account_id'); // Expense head from head of accounts
            $table->text('description')->nullable();
            $table->string('unit')->nullable(); // Unit of measurement (e.g., kg, pcs, liters)
            $table->unsignedInteger('qty')->default(1);
            $table->unsignedBigInteger('amount')->default(0);
            $table->enum('receiving_confirmation', ['pending', 'confirmed', 'rejected'])->default('pending'); // Receiving confirmation status for this item
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('head_of_account_id')->references('id')->on('head_of_accounts')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};

