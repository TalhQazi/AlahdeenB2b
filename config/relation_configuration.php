<?php

return [
    'warehouse' => [
        'index' => [
            'city',
            'locality'
        ],
        'edit' => [
            'city',
            'locality',
            'owner',
            'features',
            'images'
        ],
    ],
    'warehouse_booking' => [
        'index' => [
            'bookedBy',
            'warehouse',
            'warehouse.owner',
        ],
    ],

];
