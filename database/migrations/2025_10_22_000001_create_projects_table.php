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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name')->index();
            $table->text('description')->nullable();
            $table->text('address');
            $table->string('facing')->nullable(); // North, South, East, West
            $table->decimal('land_area', 10, 2)->nullable(); // in square feet (as number)
            $table->integer('total_floors')->nullable(); // total number of floors
            $table->integer('storey')->nullable(); // Storey/Tola (number of storey)
            
            $table->string('land_owner_name')->nullable(); // Land owner name
            $table->string('land_owner_nid')->nullable(); // Land owner NID
            $table->string('land_owner_phone')->nullable(); // Land owner phone
            
            $table->date('project_launching_date')->nullable();
            $table->date('project_hand_over_date')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'on_hold', 'cancelled'])->default('upcoming');
            
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
        Schema::dropIfExists('projects');
    }
};
