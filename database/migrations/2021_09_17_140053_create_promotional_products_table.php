<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionalProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotional_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->unsignedBigInteger('promotional_product_id')->nullable();
            $table->foreign('promotional_product_id')->references('id')->on('products')->constrained();
            $table->decimal('discount_percentage', 8, 2)->nullable();
            $table->boolean('by_date')->default(0);
            $table->boolean('by_no_of_units')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedInteger('no_of_units')->nullable();
            $table->unsignedInteger('remaining_no_of_units')->default(0);
            $table->string('description');
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('promotional_products');
    }
}
