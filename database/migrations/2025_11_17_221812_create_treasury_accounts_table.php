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
        Schema::create('treasury_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name')->index(); // e.g., "Main Cash", "SBI Account", "HDFC Account"
            $table->enum('account_type', ['cash', 'bank'])->index(); // Cash or Bank account
            $table->string('bank_name')->nullable(); // Bank name (only for bank accounts)
            $table->string('account_number')->nullable()->index(); // Bank account number (only for bank accounts)
            $table->string('branch_name')->nullable(); // Branch name (only for bank accounts)
            $table->bigInteger('opening_balance')->default(0); // Opening balance (stored in paise, max: 9,223,372,036,854,775,807 paise = ~92 trillion)
            $table->bigInteger('current_balance')->default(0); // Current balance (stored in paise, max: 9,223,372,036,854,775,807 paise = ~92 trillion)
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('treasury_accounts');
    }
};
