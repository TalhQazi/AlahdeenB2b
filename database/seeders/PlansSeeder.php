<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Rinvex\Subscriptions\Models\Plan;
use Rinvex\Subscriptions\Models\PlanFeature;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plans')->delete();
        DB::table('plan_features')->delete();

        $goldPlan = Plan::create([
            'name' => 'Gold',
            'description' => 'Gold plan',
            'price' => 2000,
            'signup_fee' => 0,
            'invoice_period' => 1,
            'invoice_interval' => 'month',
            'trial_period' => 0,
            'trial_interval' => 'day',
            'sort_order' => 1,
            'currency' => 'PKR',
        ]);

        $goldPlan->features()->saveMany([
            new PlanFeature(['name' => 'emails', 'slug' => 'emails_gold', 'value' => 50, 'sort_order' => 1, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'sms', 'slug' => 'sms_gold', 'value' => 50, 'sort_order' => 5, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'leads', 'slug' => 'leads_gold', 'value' => 50, 'sort_order' => 10, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'no_of_products', 'slug' => 'no_of_products_gold', 'value' => 50, 'sort_order' => 15, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'featured_products', 'slug' => 'featured_products_gold', 'value' => 50, 'sort_order' => 20, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'can_add_additional_detail_photos', 'slug' => 'can_add_additional_detail_photos_gold', 'value' => 1, 'sort_order' => 10, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'can_add_certificates_n_awards', 'slug' => 'can_add_certificates_n_awards_gold', 'value' => 1, 'sort_order' => 10, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'can_view_buying_selling_analytics', 'slug' => 'can_view_buying_selling_analytics_gold', 'value' => 1, 'sort_order' => 10, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'can_add_director_profile', 'slug' => 'can_add_director_profile_gold', 'value' => 1, 'sort_order' => 25, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'company_banner_products', 'slug' => 'company_banner_products_gold', 'value' => 10, 'sort_order' => 30, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'company_top_products', 'slug' => 'company_top_products_gold', 'value' => 10, 'sort_order' => 35, 'resettable_period' => 1, 'resettable_interval' => 'month']),
        ]);

        $silverPlan = Plan::create([
            'name' => 'Silver',
            'description' => 'Silver plan',
            'price' => 1500,
            'signup_fee' => 0,
            'invoice_period' => 1,
            'invoice_interval' => 'month',
            'trial_period' => 0,
            'trial_interval' => 'day',
            'sort_order' => 1,
            'currency' => 'PKR',
        ]);

        $silverPlan->features()->saveMany([
            new PlanFeature(['name' => 'emails', 'slug' => 'emails_silver', 'value' => 25, 'sort_order' => 1, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'sms', 'slug' => 'sms_silver', 'value' => 25, 'sort_order' => 5, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'leads', 'slug' => 'leads_silver', 'value' => 25, 'sort_order' => 10, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'no_of_products', 'slug' => 'no_of_products_silver', 'value' => 25, 'sort_order' => 15, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'featured_products', 'slug' => 'featured_products_silver', 'value' => 25, 'sort_order' => 20, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'can_add_additional_detail_photos', 'slug' => 'can_add_additional_detail_photos_silver', 'value' => 1, 'sort_order' => 10, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'can_add_certificates_n_awards', 'slug' => 'can_add_certificates_n_awards_silver', 'value' => 1, 'sort_order' => 10, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'company_banner_products', 'slug' => 'company_banner_products_silver', 'value' => 5, 'sort_order' => 30, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'company_top_products', 'slug' => 'company_top_products_silver', 'value' => 5, 'sort_order' => 35, 'resettable_period' => 1, 'resettable_interval' => 'month']),
        ]);

        $bronzePlan = Plan::create([
            'name' => 'Bronze',
            'description' => 'Bronze plan',
            'price' => 1000,
            'signup_fee' => 0,
            'invoice_period' => 1,
            'invoice_interval' => 'month',
            'trial_period' => 0,
            'trial_interval' => 'day',
            'sort_order' => 1,
            'currency' => 'PKR',
        ]);

        $bronzePlan->features()->saveMany([
            new PlanFeature(['name' => 'emails', 'slug' => 'emails_bronze', 'value' => 10, 'sort_order' => 1, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'sms', 'slug' => 'sms_bronze', 'value' => 10, 'sort_order' => 5, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'leads', 'slug' => 'leads_bronze', 'value' => 10, 'sort_order' => 10, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'no_of_products', 'slug' => 'no_of_products_bronze', 'value' => 4, 'sort_order' => 15, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'featured_products', 'slug' => 'featured_products_bronze', 'value' => 4, 'sort_order' => 20, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'can_add_additional_detail_photos', 'slug' => 'can_add_additional_detail_photos_bronze', 'value' => 1, 'sort_order' => 10, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'can_add_certificates_n_awards', 'slug' => 'can_add_certificates_n_awards_bronze', 'value' => 1, 'sort_order' => 10, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'company_banner_products', 'slug' => 'company_banner_products_bronze', 'value' => 3, 'sort_order' => 30, 'resettable_period' => 1, 'resettable_interval' => 'month']),
            new PlanFeature(['name' => 'company_top_products', 'slug' => 'company_top_products_bronze', 'value' => 3, 'sort_order' => 35, 'resettable_period' => 1, 'resettable_interval' => 'month']),
        ]);

        $bonusFeaturesGold = Plan::create([
          'name' => 'Bonus Features Gold',
          'description' => 'Additional Bonus Features Gold',
          'price' => 0,
          'signup_fee' => 0,
          'invoice_period' => 1,
          'invoice_interval' => 'month',
          'trial_period' => 0,
          'trial_interval' => 'day',
          'sort_order' => 1,
          'currency' => 'PKR',
        ]);

        $bonusFeaturesGold->features()->saveMany([
          new PlanFeature(['name' => 'leads', 'slug' => 'leads_bonus-features-gold', 'value' => 10, 'sort_order' => 1, 'resettable_period' => 1, 'resettable_interval' => 'day']),
        ]);

        $bonusFeaturesSilver = Plan::create([
          'name' => 'Bonus Features Silver',
          'description' => 'Additional Bonus Features Silver',
          'price' => 0,
          'signup_fee' => 0,
          'invoice_period' => 1,
          'invoice_interval' => 'month',
          'trial_period' => 0,
          'trial_interval' => 'day',
          'sort_order' => 1,
          'currency' => 'PKR',
        ]);

        $bonusFeaturesSilver->features()->saveMany([
          new PlanFeature(['name' => 'leads', 'slug' => 'leads_bonus-features-silver', 'value' => 5, 'sort_order' => 1, 'resettable_period' => 1, 'resettable_interval' => 'day']),
        ]);

        $bonusFeaturesBronze = Plan::create([
          'name' => 'Bonus Features Bronze',
          'description' => 'Additional Bonus Features Bronze',
          'price' => 0,
          'signup_fee' => 0,
          'invoice_period' => 1,
          'invoice_interval' => 'month',
          'trial_period' => 0,
          'trial_interval' => 'day',
          'sort_order' => 1,
          'currency' => 'PKR',
        ]);

        $bonusFeaturesBronze->features()->saveMany([
          new PlanFeature(['name' => 'leads', 'slug' => 'leads_bonus-features-bronze', 'value' => 1, 'sort_order' => 1, 'resettable_period' => 1, 'resettable_interval' => 'day']),
        ]);

    }
}
