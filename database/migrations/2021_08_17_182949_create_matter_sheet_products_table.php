<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatterSheetProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matter_sheet_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matter_sheet_id')->references('id')->on('matter_sheets')->onDelete('cascade');
            $table->foreignId('category_id')->constrained();
            $table->string('category')->default('category');
            $table->foreignId('user_id')->constrained();
            $table->string('title');
            $table->string('image_path');
            $table->text('description')->nullable();
            $table->decimal('price',8,2);
            $table->string('product_code', 13)->nullable();
            $table->string('web_category')->nullable();
            $table->string('brand_name')->nullable();
            $table->float('approx_price')->nullable();
            $table->string('currency_1')->nullable();
            $table->float('min_price')->nullable();
            $table->float('max_price')->nullable();
            $table->string('currency_2')->nullable();
            $table->float('min_order_quantity')->nullable();
            $table->string('unit_measure_quantity')->nullable();
            $table->float('supply_ability')->nullable();
            $table->string('unit_measure_supply')->nullable();
            $table->boolean('is_cpa_approved')->default(0);
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
        Schema::dropIfExists('matter_sheet_products');
    }
}
