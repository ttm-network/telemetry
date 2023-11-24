<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use TTM\Telemetry\Clock\SystemClock;
use TTM\Telemetry\ClockInterface;
use TTM\Telemetry\ConfigTracerFactoryProvider;
use TTM\Telemetry\TracerFactoryInterface;
use TTM\Telemetry\TracerFactoryProviderInterface;
use TTM\Telemetry\TracerInterface;

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
    ClockInterface::class => SystemClock::class
];
