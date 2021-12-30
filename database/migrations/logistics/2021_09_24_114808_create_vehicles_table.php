<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env("DB_DATABASE_LOGISTICS"))->create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('parent_id')->nullable();
            $table->string('image_path');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(env("DB_DATABASE_LOGISTICS"))->dropIfExists('vehicles');
    }
}
