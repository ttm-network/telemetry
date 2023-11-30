<?php

declare(strict_types=1);

namespace Api\Infrastructure\Telemetry;

namespace TTM\Telemetry;

interface TracerFactoryInterface
{
    public function create(?string $name = null): TracerInterface;
}
