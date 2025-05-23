<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->integer('quantity')->default(1);
            $table->enum('status', ['available', 'maintenance', 'blocked'])->default('available');
            $table->boolean('is_deliverable')->default(false); 
            $table->text('usage_notes')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
