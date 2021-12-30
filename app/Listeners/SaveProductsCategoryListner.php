<?php

namespace App\Listeners;

use App\Events\SaveProductsCategoryEvent;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveProductsCategoryListner implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SaveProductsCategoryEvent  $event
     * @return void
     */
    public function handle(SaveProductsCategoryEvent $event)
    {
        $products = Product::where('category_id', $event->category->id)->get();
        if(!empty($products)) {
            foreach($products as $product) {
                if($product->category != $event->category->title) {
                    $product = Product::find($product->id);
                    $product->category = $event->category->title;
                    $product->save();
                }
            }
        }
    }
}
