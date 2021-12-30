<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number', 25); // unique invoice number prefixed with seller initials
            $table->dateTime('invoice_date');
            $table->dateTime('payment_due_date');
            $table->dateTime('delivery_date');
            $table->unsignedBigInteger('seller_id');
            $table->json('seller_details');
            $table->unsignedBigInteger('buyer_id');
            $table->json('buyer_details');
            $table->json('terms_and_conditions')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->integer('freight_charges')->unsigned()->nullable();
            $table->enum('status', ['due', 'partial_paid', 'paid', 'cancelled', 'refunded', 'expired'])->default('due');
            $table->boolean('is_shared')->default(false);
            $table->unsignedBigInteger('updated_by');
            $table->string('invoice_path')->default('/');
            $table->timestamps();

            $table->foreign('seller_id')->references('id')->on('users');
            $table->foreign('buyer_id')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
          });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained();
            $table->string('name');
            $table->string('code');
            $table->integer('quantity')->unsigned();
            $table->string('quantity_unit');
            $table->integer('rate')->unsigned();
            $table->integer('gst')->unsigned();
            $table->timestamps();
        });

        Schema::create('invoice_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained();
            $table->enum('type', ['logo', 'purchase_order', 'delivery_receipt', 'shipment_receipt', 'signature', 'tax_certificate']);
            $table->string('path');
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
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoice_attachments');
        Schema::dropIfExists('invoices');
    }
}
