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
    Schema::create('messages', function (Blueprint $table) {
        $table->id();

        $table->foreignId('chat_id')->constrained()->onDelete('cascade');

        $table->unsignedBigInteger('sender_id');
        $table->enum('sender_type', ['user', 'merchant']);

        $table->text('message')->nullable();
        $table->string('image_url')->nullable();

        $table->boolean('read')->default(false);
        $table->timestamp('read_at')->nullable();

        $table->timestamps();
        $table->softDeletes();

        $table->index(['chat_id', 'created_at']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
