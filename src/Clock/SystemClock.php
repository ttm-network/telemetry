<?php

declare(strict_types=1);

namespace TTM\Telemetry\Clock;

use TTM\Telemetry\ClockInterface;

/**
 * @internal
 */
final class SystemClock implements ClockInterface
{
    public function now(): int
    {
        return \hrtime(true);
    }
}
