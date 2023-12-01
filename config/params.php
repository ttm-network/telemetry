<?php

declare(strict_types=1);

use TTM\Telemetry\Context\NullContextExtractor;
use TTM\Telemetry\LogTracer;
use TTM\Telemetry\NullTracer;

return [
    'ttm/telemetry' => [
        'default' => 'null',
        'drivers' => [
            'null' => NullTracer::class,
            'log' => LogTracer::class,
        ],
        'context/extractor' => [
            'default' => 'null',
            'extractors' => [
                'null' => NullContextExtractor::class
            ]
        ],
        'registry' => []
    ]
];
