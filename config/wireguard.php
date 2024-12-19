<?php

return [
    'subnet' => [
        'network' => env('SERVER_NETWORK', '10.66.0.0'),
        'gateway' => env('SERVER_GATEWAY', '10.66.0.1'),
        'mask' => env('SERVER_NET_MASK', 24)
    ]
];