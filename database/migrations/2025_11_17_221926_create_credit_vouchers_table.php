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
        Schema::create('credit_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number')->unique()->index(); // CV-000001, etc.
            $table->date('voucher_date');
            $table->text('remarks')->nullable();
            $table->bigInteger('total_amount')->default(0); // Total for validation (stored in paise, max: 9,223,372,036,854,775,807)
            $table->json('change_history')->nullable(); // History of changes: [{"field": "voucher_date", "old_value": "2025-01-01", "new_value": "2025-01-02", "changed_by": 1, "changed_at": "2025-01-01 12:00:00"}, ...]
            
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
        Schema::dropIfExists('credit_vouchers');
    }
};
