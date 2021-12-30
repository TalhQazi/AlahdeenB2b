<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatterSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matter_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('file_path')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('category_id')->constrained();
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
        Schema::dropIfExists('matter_sheets');
    }
}
