<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessDirectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_directors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable();
            $table->foreignId('user_id');
            $table->string('director_photo')->nullable();
            $table->string('name');
            $table->string('designation');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('business_directors');
    }
}
