<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseContactedStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('stats')->create('warehouse_contacted_stats', function (Blueprint $table) {
          $db = DB::connection('mysql')->getDatabaseName();

          $table->id();
          $table->unsignedBigInteger('warehouse_id');
          $table->timestamps();

          $table->foreign('warehouse_id')->references('id')->on($db . '.warehouses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('stats')->dropIfExists('warehouse_contacted_stats');
    }
}
