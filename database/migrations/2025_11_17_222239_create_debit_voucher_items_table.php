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
        Schema::create('debit_voucher_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debit_voucher_id')->index();
            $table->unsignedBigInteger('head_of_account_id')->index(); // Which account is debited
            $table->unsignedBigInteger('treasury_account_id')->nullable()->index(); // Treasury account for this item
            $table->unsignedBigInteger('purchase_order_id')->nullable()->index(); // Related purchase order
            $table->unsignedBigInteger('project_id')->nullable()->index(); // Related project
            $table->bigInteger('amount'); // Stored in paise, max: 9,223,372,036,854,775,807
            $table->text('description')->nullable();
            $table->string('bank_name')->nullable(); // Bank name for payment
            $table->string('check_number')->nullable(); // Check number or transaction ID
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('debit_voucher_id')->references('id')->on('debit_vouchers')->onDelete('cascade');
            $table->foreign('head_of_account_id')->references('id')->on('head_of_accounts')->onDelete('restrict');
            $table->foreign('treasury_account_id')->references('id')->on('treasury_accounts')->onDelete('set null');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('set null');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');

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
        Schema::dropIfExists('debit_voucher_items');
    }
};
