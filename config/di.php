<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use TTM\Telemetry\Clock\SystemClock;
use TTM\Telemetry\ClockInterface;
use TTM\Telemetry\ConfigTracerFactoryProvider;
use TTM\Telemetry\SpanCollection;
use TTM\Telemetry\StackTraceFormatter;
use TTM\Telemetry\StackTraceFormatterInterface;
use TTM\Telemetry\TracerFactoryInterface;
use TTM\Telemetry\TracerFactoryProviderInterface;
use TTM\Telemetry\TracerInterface;

/** @var array $params */
return [
    TracerInterface::class => [
        'definition' => static function (ContainerInterface $container): TracerInterface {
            return $container->get(TracerFactoryInterface::class)->make();
        },
        'reset' => function (): void {
            $this->endAllSpans();
            $this->spans = new SpanCollection();
        }
    ],
    TracerFactoryInterface::class => static function (ContainerInterface $container): TracerFactoryInterface {
        return $container->get(TracerFactoryProviderInterface::class)->getTracerFactory();
    },
    TracerFactoryProviderInterface::class => static function (ContainerInterface $container) use ($params) {
        return new ConfigTracerFactoryProvider(
            $params['ttm/telemetry']['default'],
            $params['ttm/telemetry']['drivers'],
            $container
        );
    },
    ClockInterface::class => static function (ContainerInterface $container) use ($params) {
        return $container->get($params['ttm/telemetry']['registry']['clock'] ?? SystemClock::class);
    },
    StackTraceFormatterInterface::class => StackTraceFormatter::class
];
