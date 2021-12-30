<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleFieldsAdditionalBusinessDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('additional_business_details', function (Blueprint $table) {
            $table->string('import_export_no')->after('description')->nullable();
            $table->string('company_id')->after('import_export_no')->nullable();
            $table->string('bank_name')->after('company_id')->nullable();
            $table->string('income_tax_number')->after('bank_name')->nullable();
            $table->string('ntn')->after('income_tax_number')->nullable();
            $table->integer('no_of_production_units')->after('ntn')->nullable();
            $table->string('affiliation_memberships')->after('no_of_production_units')->nullable();
            $table->integer('arn_no')->after('affiliation_memberships')->nullable();
            $table->integer('company_branches')->after('affiliation_memberships')->nullable();
            $table->string('owner_cnic')->after('company_branches')->nullable();
            $table->integer('infrastructure_size')->after('owner_cnic')->nullable();
            $table->json('cities_to_trade_with')->after('infrastructure_size')->nullable();
            $table->json('cities_to_trade_from')->after('cities_to_trade_with')->nullable();
            $table->json('shipment_modes')->after('cities_to_trade_from')->nullable();
            $table->json('payment_modes')->after('shipment_modes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('additional_business_details', function (Blueprint $table) {
            $table->dropColumn('import_export_no');
            $table->dropColumn('company_id');
            $table->dropColumn('bank_name');
            $table->dropColumn('income_tax_number');
            $table->dropColumn('ntn');
            $table->dropColumn('no_of_production_units');
            $table->dropColumn('affiliation_memberships');
            $table->dropColumn('arn_no');
            $table->dropColumn('company_branches');
            $table->dropColumn('owner_cnic');
            $table->dropColumn('infrastructure_size');
            $table->dropColumn('cities_to_trade_with');
            $table->dropColumn('cities_to_trade_from');
            $table->dropColumn('shipment_modes');
            $table->dropColumn('payment_modes');
        });
    }
}
