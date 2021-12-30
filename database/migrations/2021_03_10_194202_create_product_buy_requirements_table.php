<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBuyRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_buy_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('category_ids')->nullable();
            $table->string('required_product');
            $table->string('image_path')->nullable();
            $table->text('requirement_details');
            $table->decimal('quantity');
            $table->string('unit');
            $table->double('budget')->nullable();
            $table->enum('requirement_urgency', ['immediate', 'after one month'])->nullable();
            $table->enum('requirement_frequency', ['one time', 'regular'])->nullable();
            $table->enum('supplier_location', ['local', 'any where in pakistan'])->default('any where in pakistan');
            $table->boolean('terms_and_conditions')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_buy_requirements');
    }
}
