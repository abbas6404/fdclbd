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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Menu name
            $table->unsignedBigInteger('parent_id')->nullable(); // Parent menu ID
            $table->string('route')->nullable(); // Route name for navigation
            $table->string('print_url')->nullable(); // Print page URL for reports
            $table->string('permissions')->nullable(); // Required permissions
            $table->string('status')->default('active'); // Menu status
            $table->timestamps();
            // Foreign key constraint
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
            // Indexes
            $table->index(['parent_id', 'status']);
         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
