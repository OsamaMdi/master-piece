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
    Schema::table('reservations', function (Blueprint $table) {
        $table->string('delivery_address')->nullable()->after('comment');
    });
}

    /**
     * Reverse the migrations.
     */
    
   public function down()
{
    Schema::table('reservations', function (Blueprint $table) {
        $table->dropColumn('delivery_address');
    });
}

};
