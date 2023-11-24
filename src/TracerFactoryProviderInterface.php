<?php

declare(strict_types=1);

namespace TTM\Telemetry;

use TTM\Telemetry\Exception\TracerException;

interface TracerFactoryProviderInterface
{
    /**
     * Get a tracer instance by name.
     *
     * @throws TracerException
     */
    public function getTracerFactory(?string $name = null): TracerFactoryInterface;
}
