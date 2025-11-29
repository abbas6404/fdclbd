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
        Schema::create('contra_entry_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contra_entry_id')->index();
            $table->unsignedBigInteger('treasury_account_id')->index(); // Treasury account
            $table->enum('entry_type', ['debit', 'credit'])->index(); // Debit = FROM account, Credit = TO account
            $table->bigInteger('amount'); // Stored in paise, max: 9,223,372,036,854,775,807
            $table->text('description')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contra_entry_id')->references('id')->on('contra_entries')->onDelete('cascade');
            $table->foreign('treasury_account_id')->references('id')->on('treasury_accounts')->onDelete('restrict');
            
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
        Schema::dropIfExists('contra_entry_items');
    }
};
