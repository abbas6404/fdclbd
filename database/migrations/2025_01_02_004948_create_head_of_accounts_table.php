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
        Schema::create('head_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name')->index();
            $table->enum('account_type', ['income', 'expense'])-> index();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('head_of_accounts')->onDelete('set null');
            $table->string('account_level')->default('1')->index();
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->boolean('is_requisitions')->default(false)->index(); // Show in requisition dropdown
            $table->boolean('is_boq')->default(false)->index(); // Bill of Quantities
            $table->boolean('is_account')->default(false)->index(); // Account
            $table->string('last_used_unit')->nullable(); // Remember last used unit for this account
            $table->unsignedBigInteger('last_rate')->nullable(); // Remember last used rate for this account (not used in requisitions)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('head_of_accounts');
    }
};

