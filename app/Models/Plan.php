<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Rinvex\Subscriptions\Models\Plan as BasePlan;
use Gloudemans\Shoppingcart\Contracts\Buyable;

class Plan extends BasePlan implements Buyable
{
    use HasFactory;

    /**
     * Get the identifier of the Buyable item.
     *
     * @return int|string
     */
    public function getBuyableIdentifier($options = null)
    {
        return $this->id;
    }

    /**
     * Get the description or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableDescription($options = null)
    {
        return $this->description;
    }

    /**
     * Get the price of the Buyable item.
     *
     * @return float
     */
    public function getBuyablePrice($options = null)
    {
        return $this->price;
    }

    /**
     * Get the weight of the Buyable item.
     *
     * @return float
     */
    public function getBuyableWeight($options = null)
    {
        return 0;
    }
}
