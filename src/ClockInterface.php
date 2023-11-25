<?php

declare(strict_types=1);

namespace Yiisoft\Telemetry;

interface ClockInterface
{
    /**
     * @return int Current time in nanoseconds
     */
    public function now(): int;
}
