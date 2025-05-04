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
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('type')->after('message');
            $table->string('url')->nullable()->after('type');
            $table->unsignedBigInteger('from_user_id')->nullable()->after('user_id');

            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['from_user_id']);
            $table->dropColumn(['type', 'url', 'from_user_id']);
        });
    }
};
