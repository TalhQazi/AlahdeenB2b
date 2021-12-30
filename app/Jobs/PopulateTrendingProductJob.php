<?php

namespace App\Jobs;

use App\Models\ProductImpressionStat;
use App\Models\TrendingProduct;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PopulateTrendingProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $products = ProductImpressionStat::select('product_id')->groupBy('product_id')->orderByRaw('count(product_id) desc')->limit(10)->get()->pluck('product_id');
      $trendingProducts = [];
      foreach($products as $id) {
        $trendingProducts[] = [
          'product_id' => $id,
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
      TrendingProduct::truncate();
      TrendingProduct::insert($trendingProducts);
    }
}
