<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement(
            "CREATE TABLE IF NOT EXISTS products(
            product_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            product_name varchar(255) NOT NULL,
            product_description TEXT,
            product_price DECIMAL(10, 2) NOT NULL,
            product_stock INT UNSIGNED NOT NULL DEFAULT 0,
            img_url VARCHAR(255)  DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP          
            )"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
