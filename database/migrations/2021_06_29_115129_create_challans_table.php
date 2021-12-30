<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from');
            $table->unsignedBigInteger('to');
            $table->dateTime('challan_date');
            $table->string('items_included', 255);
            $table->string('no_of_pieces');
            $table->string('weight');
            $table->string('bilty_no');
            $table->string('courier_name');
            $table->string('digital_signature')->nullable();
            $table->string('challan_path')->default('/');
            $table->timestamps();

            $table->foreign('from')->references('id')->on('users');
            $table->foreign('to')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('challans');
    }
}
