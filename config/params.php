<?php

declare(strict_types=1);

use TTM\Telemetry\LogTracerFactory;
use TTM\Telemetry\NullTracerFactory;

return [
    'ttm/telemetry' => [
        'default' => 'null',
        'drivers' => [
            'null' => NullTracerFactory::class,
            'log' => LogTracerFactory::class,
        ]
    ]
];
