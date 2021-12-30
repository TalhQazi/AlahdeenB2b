<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryProductPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_product_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained();
            $table->decimal('total_units', 8, 2);
            $table->decimal('price_per_unit', 8, 2);
            $table->decimal('avg_cost_per_unit', 8, 2);
            $table->decimal('sales_tax_percentage', 8, 2)->default(0);
            $table->boolean('allow_below_cost_sale')->default(0);
            $table->boolean('allow_price_change')->default(0);
            $table->decimal('discount_percentage', 8, 2)->default(0);
            $table->unsignedBigInteger('promotional_product_id')->nullable();
            $table->foreign('promotional_product_id')->references('id')->on('products')->constrained();
            $table->decimal('promotional_discount_percentage', 8, 2)->nullable();
            $table->string('promotion_description')->nullable();
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
        Schema::dropIfExists('inventory_product_pricings');
    }
}
