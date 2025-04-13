<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم التصنيف
            $table->text('description')->nullable(); // وصف التصنيف (اختياري)
            $table->timestamps();
            $table->softDeletes(); // سوفت دليت
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
