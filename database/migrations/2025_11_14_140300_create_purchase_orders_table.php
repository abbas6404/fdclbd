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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_order_number')->nullable()->unique()->index(); // PO-001, PO-002, etc.
            $table->date('purchase_order_date');
            $table->date('required_date')->nullable(); // Required/Expected delivery date
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->text('remark')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('requisition_id')->nullable(); // Link to requisition
            $table->foreign('requisition_id')->references('id')->on('requisitions')->onDelete('set null');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->unsignedBigInteger('employee_id')->nullable(); // Employee
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->unsignedBigInteger('supplier_id')->nullable(); // Supplier
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};

