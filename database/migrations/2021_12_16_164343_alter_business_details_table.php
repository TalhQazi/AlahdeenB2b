<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBusinessDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `business_details` CHANGE `no_of_employees` `no_of_employees` VARCHAR(250) NULL DEFAULT NULL");
        DB::statement("ALTER TABLE `business_details` CHANGE `year_of_establishment` `year_of_establishment` VARCHAR(250) NULL DEFAULT NULL");
        DB::statement("ALTER TABLE `business_details` CHANGE `year_of_establishment` `year_of_establishment` VARCHAR(225) NULL DEFAULT NULL");
        DB::statement("ALTER TABLE `business_details` CHANGE `address` `address` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `no_of_employees` `no_of_employees` VARCHAR(191) NULL DEFAULT NULL");
        DB::statement("ALTER TABLE `users` CHANGE `phone` `phone` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL");
        DB::statement("ALTER TABLE `users` CHANGE `city_id` `city_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
