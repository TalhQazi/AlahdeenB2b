<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPaymentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_order_id')->constrained();
            $table->text('description');
            $table->enum('old_status', ['pending', 'paid', 'cancelled', 'refunded'])->default('pending');
            $table->unsignedBigInteger('added_by')->unsigned();
            $table->foreign('added_by')->references('id')->on('users');
            $table->double('amount', 8, 2)->nullable();
            $table->tinyInteger('is_closed')->default(0);
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
        Schema::dropIfExists('subscription_payment_logs');
    }
}
