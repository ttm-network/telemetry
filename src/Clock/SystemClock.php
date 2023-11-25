<?php

declare(strict_types=1);

namespace Yiisoft\Telemetry\Clock;

use Yiisoft\Telemetry\ClockInterface;

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
