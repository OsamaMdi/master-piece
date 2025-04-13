<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('chat_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // المستخدم الذي يتلقى الإشعار
            $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade'); // المحادثة المرتبطة
            $table->text('message'); // محتوى الإشعار
            $table->boolean('read')->default(false); // إذا تم قراءة الإشعار أم لا
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_notifications');
    }
}
