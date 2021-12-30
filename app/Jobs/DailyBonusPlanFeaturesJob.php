<?php

namespace App\Jobs;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DailyBonusPlanFeaturesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dailyBonuses;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->dailyBonuses = config('daily_bonus_plan_features', []);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = [];
        $activeSubscriptions = app('rinvex.subscriptions.plan_subscription')::where('ends_at', '>', Carbon::now())->orderBy('subscriber_id')->get();

        foreach($activeSubscriptions as $subscription) {
            if(!array_key_exists($subscription->subscriber_id, $users)) {
                $users[$subscription->subscriber_id] = User::find($subscription->subscriber_id);
            }

            $plan = app('rinvex.subscriptions.plan')->find($subscription->plan_id);
            if(array_key_exists($plan->slug, $this->dailyBonuses)) {
                $plan = app('rinvex.subscriptions.plan')->where('slug', 'bonus-features-'.$plan->slug)->get();
                if($users[$subscription->subscriber_id]->subscribedTo($plan[0]->id)) {
                    $bonusSubscription = app('rinvex.subscriptions.plan_subscription')->byPlanId($plan[0]->id)->ofSubscriber($users[$subscription->subscriber_id])->get();
                    $bonusSubscription[0]->renew();
                } else {
                    $users[$subscription->subscriber_id]->newSubscription(config('subscription.name'), $plan[0]);
                }
            }
        }

    }
}
