<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->string('slug')->unique();

            $table->enum('reservation_type', ['daily', 'hourly']);

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();

            $table->decimal('total_price', 10, 2)->nullable(); 
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->decimal('platform_fee', 10, 2)->nullable();

            $table->enum('status', [
                'not_started',
                'cancelled',
                'in_progress',
                'completed',
                'reported'
            ])->default('not_started');

            $table->text('comment')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

    }



}
