<?php

namespace App\Providers;

use App\Models\Inventory\ProductDefinition;
use App\Models\Inventory\ProductPricing;
use App\Models\Logistics\Driver;
use App\Models\Team;
use App\Policies\Inventory\ProductDefinitionPolicy;
use App\Policies\Inventory\ProductPricingPolicy;
use App\Policies\Logistics\DriverPolicy;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        ProductDefinition::class => ProductDefinitionPolicy::class,
        ProductPricing::class => ProductPricingPolicy::class,
        Driver::class => DriverPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // grant all permissions to super-admin
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ?: null;
        });

    }
}
