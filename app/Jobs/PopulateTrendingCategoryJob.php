<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\CategoryImpressionStat;
use App\Models\TrendingCategory;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PopulateTrendingCategoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $trendingCategories = [];
      $categories = CategoryImpressionStat::select('category_id')->groupBy('category_id')->orderByRaw('count(category_id) desc')->limit(10)->get()->pluck('category_id');
      foreach($categories as $id) {
        $trendingCategories[] = [
          'category_id' => $id,
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
      TrendingCategory::truncate();
      TrendingCategory::insert($trendingCategories);


    }
}
