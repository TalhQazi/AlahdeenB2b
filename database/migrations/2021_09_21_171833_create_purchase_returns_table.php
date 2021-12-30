<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("product_code");
            $table->string("product_name");
            $table->integer("return_quantity");
            $table->float("return_amount");
            $table->string("purchase_order_no");
            $table->string("invoice_no");
            $table->enum('status', ['Pending', 'Approved', 'Declined'])->default('Pending');
            $table->string("comments")->nullable();
            $table->foreignId("user_id");
            $table->boolean("is_return_product")->default(false);
            $table->foreignId("product_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_returns');
    }
}
