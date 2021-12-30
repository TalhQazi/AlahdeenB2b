<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->double('amount');
            $table->date('transaction_date');
            $table->foreignId('payment_method_id')->constrained();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            // $table->foreignId('bank_account_id')->nullable()->constrained();
            $table->string('ref_id')->nullable();
            $table->text('ref_text')->nullable();
            $table->string('ref_image_document')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'cancelled', 'refunded', 'received'])->default('pending');
            $table->boolean('is_closed')->default(0);
            $table->unsignedBigInteger('updated_by');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('payments');
    }
}
