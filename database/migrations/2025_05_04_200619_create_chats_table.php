<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sender_id');
            $table->enum('sender_type', ['user', 'merchant']);

            $table->unsignedBigInteger('receiver_id');
            $table->enum('receiver_type', ['user', 'merchant']);

            $table->enum('status', ['open', 'closed'])->default('open');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['sender_id', 'sender_type']);
            $table->index(['receiver_id', 'receiver_type']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
