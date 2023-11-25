<?php

declare(strict_types=1);

namespace Yiisoft\Telemetry;

use Yiisoft\Telemetry\Exception\TracerException;

interface TracerFactoryProviderInterface
{
    /**
     * Get a tracer instance by name.
     *
     * @throws TracerException
     */
    public function getTracerFactory(?string $name = null): TracerFactoryInterface;
}
