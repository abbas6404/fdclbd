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

            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('flat_id');
            $table->unsignedBigInteger('sales_agent_id')->nullable();

            $table->decimal('price_per_sqft', 10, 2);
            $table->decimal('total_price', 15, 2);
            $table->decimal('parking_charge', 10, 2)->default(0);
            $table->decimal('utility_charge', 10, 2)->default(0);
            $table->decimal('additional_work_charge', 10, 2)->default(0);
            $table->decimal('other_charge', 10, 2)->nullable();
            $table->decimal('deduction_amount', 10, 2)->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->decimal('net_price', 15, 2)->nullable();

            $table->date('sale_date');
 
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
