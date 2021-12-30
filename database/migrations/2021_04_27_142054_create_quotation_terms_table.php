<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->onDelete('cascade');
            $table->decimal('discount')->nullable();
            $table->decimal('applicable_taxes')->nullable();
            $table->decimal('shipping_taxes')->nullable();
            $table->string('delivery_period')->nullable();
            $table->string('payment_terms')->nullable();
            $table->text('additional_info')->nullable();
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
        Schema::dropIfExists('quotation_terms');
    }
}
