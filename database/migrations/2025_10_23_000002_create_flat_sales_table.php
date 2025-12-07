<?php

use Illuminate\Database\Console\Migrations\StatusCommand;
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
        Schema::create('flat_sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_number')->nullable()->unique()->index(); // SALE-001, SALE-002, etc.

            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('flat_id')->unique()->index();
            $table->unsignedBigInteger('sales_agent_id')->nullable()->index();


            $table->date('sale_date');
            
            // Nominee Information
            $table->string('nominee_name')->nullable(); // Nominee name
            $table->string('nominee_nid')->nullable(); // Nominee NID
            $table->string('nominee_phone')->nullable(); // Nominee phone
            $table->string('nominee_relationship')->nullable(); // Relationship with owner (e.g., Spouse, Son, Daughter, etc.)
 
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('flat_id')->references('id')->on('flats')->onDelete('cascade');
            $table->foreign('sales_agent_id')->references('id')->on('sales_agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flat_sales');
    }
};
