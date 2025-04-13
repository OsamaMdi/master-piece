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
            $table->string('email')->unique();
            $table->string('password');
            $table->string('identity_number')->nullable(); // الهوية الشخصية
            $table->string('profile_picture')->nullable(); // صورة الملف الشخصي
            $table->string('identity_image')->nullable(); // صورة الهوية
            $table->enum('identity_country', ['Jordan', 'Other']); // الدولة (الأردن أو غيرها)
            $table->enum('status', ['active', 'blocked', 'under_review'])->default('active');
            $table->enum('user_type', ['user', 'merchant', 'admin', 'delivery'])->default('user');
            $table->string('phone')->nullable(); // رقم الهاتف
            $table->string('address')->nullable(); // العنوان
            $table->string('city')->nullable(); // المدينة
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // سوفت دليت

        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}

