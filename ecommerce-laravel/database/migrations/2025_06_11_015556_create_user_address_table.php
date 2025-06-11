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
        DB::statement(
            "CREATE TABLE IF NOT EXISTS user_address(
                user_address_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                profile_id BIGINT UNSIGNED NOT NULL,
                address_type ENUM('WORK','HOME','OFFICE') NOT NULL,
                recipient_name VARCHAR(100) NOT NULL,
                address_line1 VARCHAR(200) NOT NULL,
                city VARCHAR(100) NOT NULL,
                is_default BOOLEAN NOT NULL DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (profile_id) REFERENCES profiles(profile_id) ON DELETE CASCADE
            )"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_address');
    }
};
