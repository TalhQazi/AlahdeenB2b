<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessContactDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_contact_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id');
            $table->string('division');
            $table->string('contact_person');
            $table->string('designation');
            $table->string('location');
            $table->string('locality')->nullable();
            $table->string('postal_code');
            $table->string('address')->nullable();
            $table->string('cell_no');
            $table->string('telephone_no')->nullable();
            $table->string('email')->nullable();
            $table->string('toll_free_no')->nullable();
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
        Schema::dropIfExists('business_contact_details');
    }
}
