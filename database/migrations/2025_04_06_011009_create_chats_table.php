<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id_1')->constrained('users')->onDelete('cascade'); // ربط المستخدم الأول
            $table->foreignId('user_id_2')->constrained('users')->onDelete('cascade'); // ربط المستخدم الثاني
            $table->enum('status', ['open', 'closed'])->default('open'); // حالة المحادثة
            $table->timestamps();
            $table->softDeletes(); // سوفت دليت
        });
    }

    public function down()
    {
        Schema::dropIfExists('chats');
    }
}
