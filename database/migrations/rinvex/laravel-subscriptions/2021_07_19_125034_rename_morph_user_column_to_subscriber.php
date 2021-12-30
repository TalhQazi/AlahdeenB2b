<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameMorphUserColumnToSubscriber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config('rinvex.subscriptions.tables.plan_subscriptions'), function (Blueprint $table) {
          $table->renameColumn('user_type', 'subscriber_type');
          $table->renameColumn('user_id', 'subscriber_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('rinvex.subscriptions.tables.plan_subscriptions'), function (Blueprint $table) {
          $table->renameColumn('subscriber_type', 'user_type');
          $table->renameColumn('subscriber_id', 'user_id');
        });
    }
}
