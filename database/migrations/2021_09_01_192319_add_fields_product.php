<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_code', 13)->after('category')->nullable();
            $table->string('web_category')->after('product_code')->nullable();
            $table->string('brand_name')->after('web_category')->nullable();
            $table->float('approx_price')->after('brand_name')->nullable();
            $table->string('currency_1')->after('approx_price')->nullable();
            $table->float('min_price')->after('currency_1')->nullable();
            $table->float('max_price')->after('min_price')->nullable();
            $table->string('currency_2')->after('max_price')->nullable();
            $table->float('min_order_quantity')->after('currency_2')->nullable();
            $table->string('unit_measure_quantity')->after('min_order_quantity')->nullable();
            $table->float('supply_ability')->after('unit_measure_quantity')->nullable();
            $table->string('unit_measure_supply')->after('supply_ability')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn("product_code");
            $table->dropColumn("web_category");
            $table->dropColumn("brand_name");
            $table->dropColumn("approx_price");
            $table->dropColumn("currency_1");
            $table->dropColumn("min_price");
            $table->dropColumn("max_price");
            $table->dropColumn("currency_2");
            $table->dropColumn("min_order_quantity");
            $table->dropColumn("unit_measure_quantity");
            $table->dropColumn("supply_ability");
            $table->dropColumn("unit_measure_supply");
        });
    }
}
