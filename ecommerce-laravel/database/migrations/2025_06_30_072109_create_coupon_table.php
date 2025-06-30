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
        DB::statement("CREATE TABLE IF NOT EXISTS coupon(
            coupon_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            coupon_code VARCHAR(100) NOT NULL UNIQUE,
            by_percent DECIMAL(5,2) NOT NULL,
            by_currency DECIMAL(10,2) NOT NULL,
            expired_date DATE NOT NULL,
            status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon');
    }
};
