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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('document_name'); // Document name
            $table->string('file_path'); // Storage path
            $table->unsignedBigInteger('file_size')->nullable(); // File size in bytes
            $table->integer('display_order')->default(0); // For ordering attachments
            
            $table->unsignedBigInteger('project_id')->nullable(); // Link to project
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->unsignedBigInteger('flat_id')->nullable(); // Link to flat
            $table->foreign('flat_id')->references('id')->on('flats')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
