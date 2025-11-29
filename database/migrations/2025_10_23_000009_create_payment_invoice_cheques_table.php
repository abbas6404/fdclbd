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
        Schema::create('payment_invoice_cheques', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cheque_id');
            $table->unsignedBigInteger('payment_invoice_id');
            
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('cheque_id')->references('id')->on('payment_cheques')->onDelete('cascade');
            $table->foreign('payment_invoice_id')->references('id')->on('payment_invoices')->onDelete('cascade');

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_invoice_cheques');
    }
};
