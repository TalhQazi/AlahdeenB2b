<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    protected $connection = 'logistics';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env("DB_DATABASE_LOGISTICS"))->create('drivers', function (Blueprint $table) {
            $db = DB::connection('mysql')->getDatabaseName();

            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('image');
            $table->date('dob');
            $table->string('license_number');
            $table->string('license_photo');
            $table->date('license_expiry_date');
            $table->string('cnic_front');
            $table->string('cnic_back');
            $table->string('referral_code')->nullable();
            $table->foreign('user_id')->references('id')->on($db . '.users');
            $table->boolean('is_verified')->default(0);

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
        Schema::connection(env("DB_DATABASE_LOGISTICS"))->dropIfExists('drivers');
    }
}
