<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type'); // App\Models\User أو App\Models\Merchant

            $table->unsignedBigInteger('receiver_id');
            $table->string('receiver_type'); // App\Models\User أو App\Models\Merchant

            $table->enum('status', ['open', 'closed'])->default('open');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['sender_id', 'sender_type']);
            $table->index(['receiver_id', 'receiver_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
