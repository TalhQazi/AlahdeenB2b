<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('item');
            $table->text('description');
            $table->enum('booking_status', ['pending', 'approved', 'confirmed'])->default('pending');
            $table->double('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->double('area')->nullable(); //needs to be stored in sq.ft by default
            $table->enum('type', ['partial', 'fully'])->default('fully');
            $table->double('goods_value')->nullable();
            $table->unsignedBigInteger('booked_by');
            $table->foreign('booked_by')->references('id')->on('users');
            $table->softDeletes();
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
        Schema::dropIfExists('warehouse_bookings');
    }
}
