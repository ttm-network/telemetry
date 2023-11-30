<?php

declare(strict_types=1);

use TTM\Telemetry\LogTracer;
use TTM\Telemetry\NullTracer;

return [
    'ttm/telemetry' => [
        'default' => 'null',
        'drivers' => [
            'null' => NullTracer::class,
            'log' => LogTracer::class,
        ],
        'registry' => []
    ]
];
