<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcceptedRidesTable extends Migration
{
    protected $connection = 'logistics';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection($this->connection)->create('accepted_rides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_request_id')->constrained();
            $table->foreignId('driver_id')->constrained();
            $table->float('offer');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
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
        Schema::connection($this->connection)->dropIfExists('accepted_rides');
    }
}
