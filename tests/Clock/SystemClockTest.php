<?php

declare(strict_types=1);

namespace TTM\Tests\Telemetry;

use PHPUnit\Framework\TestCase;
use TTM\Telemetry\Clock\SystemClock;

final class SystemClockTest extends TestCase
{
    public function testNow(): void
    {
        $clock = new SystemClock();

        $this->assertIsInt($clock->now());
    }
}
