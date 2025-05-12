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
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedInteger('product_limit')->default(10);
    });
}
public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('product_limit');
    });
}

    /**
     * Reverse the migrations.
     */

};
