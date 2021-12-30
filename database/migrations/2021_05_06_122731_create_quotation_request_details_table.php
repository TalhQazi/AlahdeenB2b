<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationRequestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_request_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_request_id')->onDelete('cascade');
            $table->foreignId('product_id')->onDelete('cascade');
            $table->string('product');
            $table->double('budget');
            $table->decimal('quantity');
            $table->string('unit');
            $table->text('requirements')->nullable();
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
        Schema::dropIfExists('quotation_request_details');
    }
}
