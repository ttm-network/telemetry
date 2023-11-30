<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use TTM\Telemetry\Clock\SystemClock;
use TTM\Telemetry\ClockInterface;
use TTM\Telemetry\Collection\SpanCollection;
use TTM\Telemetry\StackTraceFormatter;
use TTM\Telemetry\StackTraceFormatterInterface;
use TTM\Telemetry\TracerFactory;
use TTM\Telemetry\TracerFactoryInterface;
use TTM\Telemetry\TracerInterface;

/** @var array $params */
return [
    TracerInterface::class => [
        'definition' => static function (ContainerInterface $container): TracerInterface {
            return $container->get(TracerFactoryInterface::class)->create();
        },
        'reset' => function (): void {
            $this->endAllSpans();
            $this->spans = new SpanCollection();
        }
    ],
    TracerFactoryInterface::class => [
        'class' => TracerFactory::class,
        '__construct()' => [
            'default' => $params['ttm/telemetry']['default'],
            'drivers' => $params['ttm/telemetry']['drivers']
        ]
    ],
    ClockInterface::class => static function (ContainerInterface $container) use ($params) {
        return $container->get($params['ttm/telemetry']['registry']['clock'] ?? SystemClock::class);
    },
    StackTraceFormatterInterface::class => StackTraceFormatter::class
];
