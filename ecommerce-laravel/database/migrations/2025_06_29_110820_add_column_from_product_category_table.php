<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE product_category
            ADD COLUMN description TEXT AFTER name,
            ADD COLUMN status ENUM('active', 'inactive') NOT NULL DEFAULT 'active' AFTER description;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE product_category
            DROP COLUMN description,
            DROP COLUMN status;
        ");
    }
};
