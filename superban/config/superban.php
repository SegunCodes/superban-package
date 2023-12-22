<?php 

return [
    'cache_driver' => env('SUPERBAN_CACHE_DRIVER', 'redis'),
    'ban_criteria' => [
        'user_id',
        'ip_address',
        'email',
    ],
    // Other configuration options...
];
