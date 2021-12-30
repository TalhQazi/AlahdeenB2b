<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRideOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection($env("DB_DATABASE_LOGISTICS"))->create('ride_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_request_id')->constrained();
            $table->foreignId('driver_id')->constrained();
            $table->float('offer');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($env("DB_DATABASE_LOGISTICS"))->dropIfExists('ride_offers');
    }
}
