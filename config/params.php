<?php

declare(strict_types=1);

use Yiisoft\Telemetry\LogTracerFactory;
use Yiisoft\Telemetry\NullTracerFactory;

return [
    'ttm/telemetry' => [
        'default' => 'null',
        'drivers' => [
            'null' => NullTracerFactory::class,
            'log' => LogTracerFactory::class,
        ]
    ]
];
