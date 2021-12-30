<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    protected $connection = 'logistics';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection($this->connection)->create('receipts', function (Blueprint $table) {
            $db = DB::connection('mysql')->getDatabaseName();

            $table->id();
            $table->foreignId('booking_request_id')->constrained();
            $table->unsignedBigInteger('customer_id');
            $table->foreignId('driver_id')->constrained();
            $table->float('fare');
            $table->float('commission');

            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on($db . '.users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($this->connection)->dropIfExists('receipts');
    }
}
