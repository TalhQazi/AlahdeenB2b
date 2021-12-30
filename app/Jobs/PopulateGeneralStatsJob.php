<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\GeneralStat;
use App\Models\Product;
use App\Models\QuotationRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PopulateGeneralStatsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $date = Carbon::today()->subDays(7);
        $quotationRequestCount = QuotationRequest::where('created_at', '>=', $date)->count();

        $categoriesCount = Category::count();

        $activeSuppliersCount = User::select('users.id')->join('products', 'users.id', '=', 'products.user_id')->where('is_active', 1)->distinct('users.id')->count();

        GeneralStat::truncate();

        GeneralStat::create([
          'no_of_rfqs' => $quotationRequestCount,
          'no_of_categories' => $categoriesCount,
          'no_of_active_suppliers' => $activeSuppliersCount
        ]);
    }

}
