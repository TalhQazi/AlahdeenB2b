<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBookingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(env("DB_DATABASE_LOGISTICS"))->create('booking_requests', function (Blueprint $table) {
            $db = DB::connection('mysql')->getDatabaseName();

            $table->id();
            $table->foreignId('vehicle_id')->constrained();
            $table->enum('delivery_type', ['inner-city', 'inter-city', 'international']);
            $table->unsignedBigInteger('pick_up_city_id');
            $table->string('shipper_name');
            $table->string('shipper_contact_number');
            $table->string('shipper_address');
            $table->decimal('shipper_lat', 10, 8);
            $table->decimal('shipper_lng', 11, 8);
            $table->unsignedBigInteger('drop_off_city_id');
            $table->string('receiver_name');
            $table->string('receiver_contact_number');
            $table->string('receiver_address');
            $table->decimal('receiver_lat', 10, 8);
            $table->decimal('receiver_lng', 11, 8);
            $table->string('product');
            $table->unsignedBigInteger('product_id');
            $table->string('type_of_packing');
            $table->integer('number_of_packets');
            $table->string('detailed_description');
            $table->float('weight');
            $table->string('weight_unit');
            $table->float('volume');
            $table->string('volume_unit');
            $table->date('departure_date');
            $table->time('departure_time');
            $table->float('bid_offer');
            $table->string('comments_and_wishes');
            $table->boolean('terms_agreed')->default(0);
            $table->unsignedBigInteger('shipment_requestor');

            $table->foreign('pick_up_city_id')->references('id')->on($db . '.cities');
            $table->foreign('drop_off_city_id')->references('id')->on($db . '.cities');
            $table->foreign('shipment_requestor')->references('id')->on($db . '.users');

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
        Schema::connection(env("DB_DATABASE_LOGISTICS"))->dropIfExists('booking_requests');
    }
}
