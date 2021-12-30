<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePromotionColumnsInventoryProductPricings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_product_pricings', function (Blueprint $table) {
            $table->dropForeign('inventory_product_pricings_promotional_product_id_foreign');
            $table->dropColumn('promotional_product_id');
            $table->dropColumn('promotional_discount_percentage');
            $table->dropColumn('promotion_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_product_pricings', function (Blueprint $table) {
            $table->unsignedBigInteger('promotional_product_id')->nullable()->after('discount_percentage');
            $table->foreign('promotional_product_id')->references('id')->on('products')->constrained();
            $table->decimal('promotional_discount_percentage', 8, 2)->nullable()->after('promotional_product_id');
            $table->string('promotion_description')->nullable()->after('promotional_discount_percentage');
        });
    }
}
