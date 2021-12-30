<?php

namespace App\Jobs;

use App\Models\Warehouse\PopularWarehouse;
use App\Models\Warehouse\WarehouseViewStat;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PopulatePopularWarehouseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $warehouses = WarehouseViewStat::select('warehouse_id')->groupBy('warehouse_id')->orderByRaw('count(warehouse_id) desc')->limit(10)->get()->pluck('warehouse_id');
      $popularWarehouses = [];
      foreach($warehouses as $id) {
        $popularWarehouses[] = [
          'warehouse_id' => $id,
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }

      PopularWarehouse::truncate();
      PopularWarehouse::insert($popularWarehouses);
    }
}
