<?php

declare(strict_types=1);

namespace Yiisoft\Tests\Telemetry;

use PHPUnit\Framework\TestCase;
use Yiisoft\Telemetry\Clock\SystemClock;

final class SystemClockTest extends TestCase
{
    public function testNow(): void
    {
        $clock = new SystemClock();

        $this->assertIsInt($clock->now());
    }
}
