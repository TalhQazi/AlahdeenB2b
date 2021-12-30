<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalBusinessDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_business_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id');
            $table->string('logo', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('start_day',9);
            $table->string('start_time',7);
            $table->string('end_day',9);
            $table->string('end_time',7);
            $table->json('states')->nullable();
            $table->json('included_cities')->nullable();
            $table->json('excluded_cities')->nullable();
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
        Schema::dropIfExists('additional_business_details');
    }
}
