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
        Schema::create('credit_voucher_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credit_voucher_id')->index();
            $table->unsignedBigInteger('head_of_account_id')->index(); // Which account is credited
            $table->bigInteger('amount'); // Stored in paise, max: 9,223,372,036,854,775,807
            $table->text('description')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('credit_voucher_id')->references('id')->on('credit_vouchers')->onDelete('cascade');
            $table->foreign('head_of_account_id')->references('id')->on('head_of_accounts')->onDelete('restrict');
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
        Schema::dropIfExists('credit_voucher_items');
    }
};
