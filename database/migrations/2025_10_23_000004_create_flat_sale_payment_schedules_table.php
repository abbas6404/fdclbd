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
            Schema::create('flat_sale_payment_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('flat_id');
                $table->string('term_name');
                $table->integer('receivable_amount');
                $table->integer('received_amount')->default(0); // Amount actually paid so far
                
                $table->dateTime('due_date')->nullable();
                $table->enum('status', ['pending','partial', 'paid'])->default('pending'); 
                // receive_amount

                $table->timestamps();
                $table->softDeletes();

                $table->unsignedBigInteger('created_by')->nullable();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

                $table->foreign('flat_id')->references('id')->on('flats')->onDelete('cascade');

            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('flat_sale_payment_schedules');
        }
    };
