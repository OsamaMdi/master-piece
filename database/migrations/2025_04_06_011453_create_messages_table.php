<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade'); // المحادثة التي تنتمي إليها الرسالة
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // المرسل
            $table->text('message')->nullable(); // النص (اختياري إذا كانت الرسالة تحتوي على صورة فقط)
            $table->string('image_url')->nullable(); // رابط الصورة (اختياري)
            $table->boolean('read')->default(false); // إذا كانت الرسالة قد تمت قراءتها
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
//
