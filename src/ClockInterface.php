<?php

declare(strict_types=1);

namespace TTM\Telemetry;

interface ClockInterface
{
    /**
     * @return int Current time in nanoseconds
     */
    public function now(): int;
}
