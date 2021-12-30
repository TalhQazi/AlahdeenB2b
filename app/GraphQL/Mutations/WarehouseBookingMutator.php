<?php

namespace App\GraphQL\Mutations;

use App\Models\Warehouse\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Warehouse\WarehouseBookingController;

class WarehouseBookingMutator
{

    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue
     * @param  mixed[]  $args
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context
     * @return mixed
     */
    public function create($rootValue, array $args, GraphQLContext $context)
    {
        $wareHouseBookingObj = new WarehouseBookingController();
        $warehouse = Warehouse::find($args['warehouse_id']);
        return $wareHouseBookingObj->saveWarehouseBooking($warehouse, $args);
    }
}
