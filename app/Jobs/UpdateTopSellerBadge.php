<?php

namespace App\Jobs;

use App\Models\Badge;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\UserBadge;
use App\Models\Invoice;

class UpdateTopSellerBadge implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $topSeller;

    public function __construct()
    {
      $this->topSeller = config('badges.top_seller');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $invoices = Invoice::with('seller')
                      ->select('seller_id', DB::raw('COUNT(*) as total_invoices'))
                      ->groupBy('seller_id')
                      ->having('total_invoices', '>', 0)
                      ->orderBy('total_invoices', 'DESC')
                      ->limit($this->topSeller['max_limit'])
                      ->get();

        if (count($invoices) > 0) {
            $topSellerBadge = Badge::find($this->topSeller['id']);

            // remove existing top sellers
            UserBadge::where('badge_id', $this->topSeller['id'])->delete();

            // assign badge to new top sellers
            foreach ($invoices as $invoice) {
                $invoice->seller->badges()->attach($topSellerBadge);
            }
        }
    }
}
