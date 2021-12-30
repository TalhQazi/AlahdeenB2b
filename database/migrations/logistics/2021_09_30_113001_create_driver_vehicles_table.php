<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env("DB_DATABASE_LOGISTICS"))->create('driver_vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained();
            $table->foreignId('vehicle_id')->constrained();
            $table->string('company_name');
            $table->string('number_plate_no');
            $table->string('photo');
            $table->string('registration_certificate');
            $table->string('fitness_certificate');
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
        Schema::connection(env("DB_DATABASE_LOGISTICS"))->dropIfExists('driver_vehicles');
    }
}
