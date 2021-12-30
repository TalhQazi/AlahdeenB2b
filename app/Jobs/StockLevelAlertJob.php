<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use App\Notifications\StockAlertNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class StockLevelAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $productWithDefinitions = Product::select(
            'products.id', 'products.title', 'ipp.total_units', 'products.user_id',
                DB::raw('sum(ii.quantity) as total_sold_units'),
            )
            ->join('inventory_product_definitions as ipd', 'products.id', '=', 'ipd.product_id')
            ->join('inventory_product_pricings as ipp', 'products.id', '=', 'ipp.product_id')
            ->join('invoice_items as ii', 'products.id', '=', 'ii.product_id')
            ->join('users as u', 'products.user_id', '=', 'u.id')
            ->where('u.is_active', 1)
            ->whereNotNull('ipd.id')
            ->groupBy('products.id')
            ->get();

        foreach($productWithDefinitions as $product) {
            $product->remaining_products = !empty($product->total_units) ? $product->total_units - $product->total_sold_units : 0;
            $quantityLevel = !empty($product->total_units) ? ($product->remaining_products / $product->total_units) * 100 : 0;
            if($quantityLevel <= 25) {
                $product->quantity_status = __('below recommended level');
                Notification::send(User::find($product->user_id), new StockAlertNotification($product->title, $product->quantity_status, $quantityLevel));
            } else if($quantityLevel > 25 && $quantityLevel <= 50) {
                $product->quantity_status = __('maintained recommended level');
                Notification::send(User::find($product->user_id), new StockAlertNotification($product->title, $product->quantity_status, $quantityLevel));
            }
        }
    }
}
