<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait PackageUsageTrait
{

  public function checkSubscriptionLeads(User $user, $featureType)
  {

    if ($user->activeSubscriptions()->isNotEmpty()) {
      if($this->getFeatureLimit($user->activeSubscriptions(), $featureType) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }


  public function canUseFeature($featureType)
  {
    if (Auth::user()->activeSubscriptions()->isNotEmpty()) {
      if($this->getFeatureLimit(Auth::user()->activeSubscriptions(), $featureType) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function getSubscription($subscriptions, $featureType) {
    $bonusPlanId = [];
    $subscribedPlans = $subscriptions->pluck('plan_id')->toArray();

    //Check for bonus plans and return if any having remaining features available
    $plans = app('rinvex.subscriptions.plan')->where('slug', 'like', '%'.'bonus'.'%')->whereIn('id', $subscribedPlans)->get();
    if(!empty($plans)) {
      foreach($plans as $index => $plan) {
        $hasFeatureType = $plan->features()->where('slug', $featureType . '_' . $plan->slug)->get();
        $subscription = $plan->subscriptions()->where('subscriber_id', Auth::user()->id)->where('plan_id', $plan->id)->get();
        // dd($subscription);
        array_push($bonusPlanId, $plan->id);
        if(!empty($hasFeatureType)) {
          if ($subscription[0]->getFeatureRemainings($featureType . '_' . $plan->slug) > 0) {
            return $subscription[0];
          }
        }
      }
    }

    //If no bonus plans then check if any other plans have remaining feature available

    //unsetting bonus subscribed plans since we have already checked against them earlier
    if(!empty($bonusPlanId)) {
      foreach($bonusPlanId as $planId) {
        if (($key = array_search($planId, $subscribedPlans)) !== false) {
          unset($subscribedPlans[$key]);
        }
      }
    }

    $plans = app('rinvex.subscriptions.plan')->whereIn('id', $subscribedPlans)->get();
    if(!empty($plans)) {
      foreach($plans as $index => $plan) {
        $hasFeatureType = $plan->features()->where('slug', $featureType . '_' . $plan->slug)->get();
        $subscription = $plan->subscriptions()->where('subscriber_id', Auth::user()->id)->where('plan_id', $plan->id)->get();
        if(!empty($hasFeatureType)) {

          if ($subscription[0]->getFeatureRemainings($featureType . '_' . $plan->slug) > 0) {
            return $subscription[0];
          }
        }
      }
    }


    return false;
  }

  public function consumeLead($subscription, $featureType)
  {
    $plan = app('rinvex.subscriptions.plan')->findOrFail($subscription->plan_id);
    return $subscription->recordFeatureUsage($featureType.'_' . $plan->slug);
  }

  public function getFeatureLimit($subscriptions, $featureType)
  {
    $limit = 0;
    if(!empty($subscriptions)) {

      foreach ($subscriptions as $subscription) {

        $plan = app('rinvex.subscriptions.plan')->find($subscription->plan_id);
        $hasFeatureType = $plan->features()->where('slug', $featureType . '_' . $plan->slug)->get();
        if(!empty($hasFeatureType)) {
          $limit += $subscription->getFeatureValue($featureType . '_' . $plan->slug);
        } else {
          continue;
        }
      }
    }

    return $limit;
  }
}
