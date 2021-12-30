<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryProductDefinitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_product_definitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained();
            $table->string('product_code')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('purchase_unit')->nullable();
            $table->integer('conversion_factor')->default(1);
            $table->string('product_group')->nullable();
            $table->string('supplier')->nullable();
            $table->string('product_gender')->nullable();
            $table->string('accquire_type')->nullable();
            $table->string('value_addition')->nullable();
            $table->string('life_type')->nullable();
            $table->string('tax_code')->nullable();
            $table->string('purchase_type')->nullable();
            $table->string('additional_attributes')->nullable();
            $table->string('technical_details')->nullable();
            $table->string('additional_description')->nullable();
            $table->integer('purchase_production_interval')->default(1);
            $table->enum('purchase_production_unit', ['days', 'months', 'years'])->default('days');
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
        Schema::dropIfExists('inventory_product_definitions');
    }
}
