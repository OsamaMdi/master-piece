<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE reports MODIFY COLUMN target_type ENUM('product', 'review', 'general', 'reservation')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        DB::statement("ALTER TABLE reports MODIFY COLUMN target_type ENUM('product', 'review', 'general', 'reservation')");
    }
};
