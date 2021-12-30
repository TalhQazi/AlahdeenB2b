<?php

namespace App\Listeners;

use App\Events\RecordStatsEvent;
use App\Models\CategoryImpressionStat;
use App\Models\CategoryViewStat;
use App\Models\ProductContactedStat;
use App\Models\ProductViewStat;
use App\Models\ProductImpressionStat;
use App\Models\Warehouse\WarehouseContactedStat;
use App\Models\Warehouse\WarehouseImpressionStat;
use App\Models\Warehouse\WarehouseViewStat;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordStats implements ShouldQueue
{

    private $modelTypes = [
        'category' => [
            'no_of_views' => CategoryViewStat::class,
            'no_of_impressions' => CategoryImpressionStat::class,
        ],
        'product' => [
            'no_of_views' => ProductViewStat::class,
            'no_of_impressions' => ProductImpressionStat::class,
            'contacted_supplier' => ProductContactedStat::class,
        ],
        'warehouse' => [
          'no_of_views' => WarehouseViewStat::class,
          'no_of_impressions' => WarehouseImpressionStat::class,
          'contacted_owner' => WarehouseContactedStat::class,
      ],
    ];

    private $idTypes = [
        'category' => 'category_id',
        'product' => 'product_id',
        'warehouse' => 'warehouse_id'
    ];

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  RecordStatsEvent  $event
     * @return void
     */
    public function handle(RecordStatsEvent $event)
    {
        $model = $this->modelTypes[$event->modelType][$event->statsType];
        $idType = $this->idTypes[$event->modelType];
        if(!empty($event->data)) {
            foreach($event->data as $key => $data) {
                $model::create([
                    $idType => $data->id,
                ]);
            }
        }

        return true;
    }

}
