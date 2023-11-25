<?php

declare(strict_types=1);

namespace Api\Infrastructure\Telemetry;

namespace Yiisoft\Telemetry;

interface TracerFactoryInterface
{
    /**
     * Make tracer object with given context
     */
    public function make(array $context = []): TracerInterface;
}
