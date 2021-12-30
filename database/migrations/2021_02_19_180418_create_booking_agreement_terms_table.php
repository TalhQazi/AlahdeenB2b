<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingAgreementTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_agreement_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('item');
            $table->text('description');
            $table->double('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->double('area')->nullable(); //needs to be stored in sq.ft by default
            $table->enum('type', ['partial', 'fully'])->default('fully');
            $table->double('goods_value')->nullable();
            $table->double('price');
            $table->text('user_terms');
            $table->text('owner_terms');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('creator_role');
            $table->boolean('requestor_payment_status')->default(0);
            $table->boolean('owner_paid_status')->default(0);
            $table->double('tax_percentage')->default(0);
            $table->double('tax_amount')->default(0);
            $table->double('commission_percentage')->default(config('commission.warehouse_booking_commission'));
            $table->double('commission_paid')->nullable();
            $table->double('total_paid_to_owner')->nullable();
            $table->unsignedBigInteger('payment_method_id');
            $table->enum('status', ['pending', 'cancelled', 'confirmed', 'refunded'])->default('pending');
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('warehouse_bookings')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('creator_role')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade')->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_booking_agreement_terms');
    }
}
