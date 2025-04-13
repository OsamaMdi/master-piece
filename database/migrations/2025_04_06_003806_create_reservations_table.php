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
            $table->enum('reservation_type', ['daily', 'hourly']);  // نوع الحجز (يومي أو بالساعة)


            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();


            $table->dateTime('start_datetime')->nullable();  // تاريخ ووقت البداية
            $table->dateTime('end_datetime')->nullable();    // تاريخ ووقت النهاية

            $table->decimal('total_price', 10, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->text('comment')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }


    
}
