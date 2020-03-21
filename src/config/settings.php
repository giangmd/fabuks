<?php
return [
    'role'          => ['admin', 'manager', 'user'],
    'limit'         => env('PAGINATION', 10),
    'max_offer'     => env('MAX_OFFER', 5),
    'init_gain'     => env('INIT_GAIN', 1000),
    'fabuk_symbool' => env('FABUK_SYMBOOL'),
    'fabuk_unit'    => env('FABUK_UNIT'),
    'currenies'     => explode(',', env('CURRENCIES')),
    'status_trade'  => ['pendding', 'success'],
];