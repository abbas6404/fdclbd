<?php

use Illuminate\Database\Eloquent\SoftDeletingScope;
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
        Schema::create('flats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade')->index();
            $table->string('flat_number')->index(); // A-101, B-205, etc.
            $table->string('flat_type')->nullable()->index();  // 1BHK,2BHK,3BHK,4BHK,Penthouse,Duplex,Commercial
            
            $table->string('floor_number')->nullable()->index();
            $table->decimal('flat_size', 10, 2)->nullable(); // in square feet (as number)
          
            
            $table->enum('status', ['available', 'sold', 'reserved', 'land_owner'])->default('available')->index();

            // Unique constraint: flat_number must be unique within a project
            $table->unique(['project_id', 'flat_number']);

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
        Schema::dropIfExists('flats');
    }
};
