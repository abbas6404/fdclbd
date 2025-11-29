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
        Schema::create('payment_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('payment_schedule_id')->nullable(); // Link to schedule if applicable
            $table->integer('amount');
            


            $table->timestamps();
            $table->softDeletes();

            $table->foreign('invoice_id')->references('id')->on('payment_invoices')->onDelete('cascade');
            $table->foreign('payment_schedule_id')->references('id')->on('flat_sale_payment_schedules')->onDelete('set null');
 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_invoice_items');
    }
};
