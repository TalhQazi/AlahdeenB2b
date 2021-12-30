<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('stats')->create('general_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('no_of_rfqs'); //Last 7 days
            $table->unsignedBigInteger('no_of_categories');
            $table->unsignedBigInteger('no_of_active_suppliers');
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
        Schema::connection('stats')->dropIfExists('general_stats');
    }
}
