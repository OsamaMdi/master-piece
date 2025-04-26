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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المبلّغ

            $table->string('reportable_type'); // اسم الجدول (Product / Review / null للموقع العام)
            $table->unsignedBigInteger('reportable_id')->nullable(); // id الكيان المُبلّغ عنه

            $table->enum('target_type', ['product', 'review', 'general']); // عشان نفلتر بسهولة
            $table->string('subject')->nullable(); // عنوان البلاغ
            $table->text('message'); // نص البلاغ

            $table->enum('status', ['pending', 'reviewed', 'resolved'])->default('pending');

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
