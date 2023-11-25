<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Yiisoft\Telemetry\Clock\SystemClock;
use Yiisoft\Telemetry\ClockInterface;
use Yiisoft\Telemetry\ConfigTracerFactoryProvider;
use Yiisoft\Telemetry\LogTracer;
use Yiisoft\Telemetry\TracerFactoryInterface;
use Yiisoft\Telemetry\TracerFactoryProviderInterface;
use Yiisoft\Telemetry\TracerInterface;

/** @var array $params */
return [
    TracerInterface::class => static function (ContainerInterface $container): TracerInterface {
        return $container->get(TracerFactoryInterface::class)->make();
    },
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
    ClockInterface::class => SystemClock::class,
    LogTracer::class => [
        'class' => LogTracer::class,
        'reset' => function (): void {
        }
    ]
];