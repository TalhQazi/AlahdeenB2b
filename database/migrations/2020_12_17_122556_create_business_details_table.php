<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('company_name');
            $table->string('country',100)->default('Pakistan');
            $table->foreignId('city_id')->nullable()->constrained();
            $table->string('address');
            $table->string('locality')->nullable();
            $table->integer('zip_code')->nullable();
            $table->string('phone_number',20);
            $table->string('alternate_website')->nullable();
            $table->year('year_of_establishment');
            $table->integer('no_of_employees');
            $table->string('annual_turnover')->nullable();
            $table->string('ownership_type')->nullable();
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
        Schema::dropIfExists('business_details');
    }
}
