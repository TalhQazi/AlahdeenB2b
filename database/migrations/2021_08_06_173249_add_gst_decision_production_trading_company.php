<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGstDecisionProductionTradingCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_details', function (Blueprint $table) {
            $table->string('gst_no')->after('company_name')->nullable();
            $table->boolean('is_decision_maker')->after('gst_no')->default(0);
            $table->integer('monthly_production_cap')->after('is_decision_maker')->nullable();
            $table->string('trade_brand_name')->after('monthly_production_cap')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_details', function (Blueprint $table) {
            $table->dropColumn('gst_no');
            $table->dropColumn('is_decision_maker');
            $table->dropColumn('monthly_production_cap');
            $table->dropColumn('trade_brand_name');
        });
    }
}
