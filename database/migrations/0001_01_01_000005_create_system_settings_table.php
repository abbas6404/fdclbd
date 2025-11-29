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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean,
            $table->string('group')->default('general'); // general, prefix, system, maintenance
            $table->text('description')->nullable();
          
            $table->json('option')->nullable(); // For additional options (metadata, configs)

            $table->integer('is_public')->default(0); // 0: private, 1: public
            $table->timestamps();
            
         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
