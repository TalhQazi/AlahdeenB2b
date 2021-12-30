<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('city_id')->constrained();
            $table->foreignId('locality_id')->constrained();
            $table->foreignId('property_type_id')->constrained();
            $table->point('coordinates');
            $table->double('area');
            $table->double('price');
            $table->boolean('can_be_shared')->default(0);
            $table->boolean('is_approved')->default(0);
            $table->boolean('instant_booking')->default(0);
            $table->boolean('is_verified')->default(0);
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('warehouses');
    }
}
