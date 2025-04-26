<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('identity_number')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('identity_image')->nullable();
            $table->enum('identity_country', ['Jordan', 'Other'])->nullable();
            $table->enum('status', ['active', 'blocked', 'under_review'])->default('active');
            $table->enum('user_type', ['user', 'merchant', 'admin'])->default('user');
            $table->text('block_reason')->nullable();
            $table->dateTime('blocked_until')->nullable();
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}

