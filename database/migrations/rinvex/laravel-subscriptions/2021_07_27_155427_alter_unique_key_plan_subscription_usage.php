<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUniqueKeyPlanSubscriptionUsage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config('rinvex.subscriptions.tables.plan_subscription_usage'), function (Blueprint $table) {

          $table->dropForeign('plan_subscription_usage_feature_id_foreign');
          $table->dropForeign('plan_subscription_usage_subscription_id_foreign');

          $table->dropUnique('plan_subscription_usage_subscription_id_feature_id_unique');
          $table->unique(['subscription_id', 'feature_id', 'deleted_at'], 'plan_subscription_usage_unique');

          $table->foreign('subscription_id')->references('id')->on(config('rinvex.subscriptions.tables.plan_subscriptions'))
          ->onDelete('cascade')->onUpdate('cascade');
          $table->foreign('feature_id')->references('id')->on(config('rinvex.subscriptions.tables.plan_features'))
          ->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table(config('rinvex.subscriptions.tables.plan_subscription_usage'), function (Blueprint $table) {

          $table->dropForeign('plan_subscription_usage_feature_id_foreign');
          $table->dropForeign('plan_subscription_usage_subscription_id_foreign');


          $table->dropUnique('plan_subscription_usage_unique');
          $table->unique(['subscription_id', 'feature_id']);

          $table->foreign('subscription_id')->references('id')->on(config('rinvex.subscriptions.tables.plan_subscriptions'))
          ->onDelete('cascade')->onUpdate('cascade');
          $table->foreign('feature_id')->references('id')->on(config('rinvex.subscriptions.tables.plan_features'))
          ->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
