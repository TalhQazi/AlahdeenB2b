<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCnicTaxRelatedVerificationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cnic', 13)->after('invoices_received')->nullable();
            $table->string('ntn')->after('cnic')->nullable();
            $table->string('stn')->after('ntn')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cnic');
            $table->dropColumn('ntn');
            $table->dropColumn('stn');
        });
    }
}
