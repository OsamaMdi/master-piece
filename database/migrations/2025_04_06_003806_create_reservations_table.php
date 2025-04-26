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

            $table->decimal('total_price', 10, 2)->nullable(); // السعر الكلي
            $table->decimal('paid_amount', 10, 2)->nullable(); // المدفوع (10% أو كامل)
            $table->decimal('platform_fee', 10, 2)->nullable(); // نسبة 5% من total_price

            $table->enum('status', [
                'not_started',  // ✅ الحجز لم يبدأ بعد (default)
                'cancelled',    // تم الإلغاء
                'in_progress',  // الحجز حالياً عند الزبون
                'completed',    // الحجز انتهى
                'reported'      // عليه بلاغ
            ])->default('not_started');

            $table->text('comment')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

    }



}
