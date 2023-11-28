<?php

declare(strict_types=1);

namespace TTM\Telemetry;

use Throwable;

interface StackTraceFormatterInterface
{
    /**
     * Formats an exception in a java-like format.
     *
     * @param Throwable $e exception to format
     * @return string formatted exception
     *
     * @see https://docs.oracle.com/en/java/javase/17/docs/api/java.base/java/lang/Throwable.html#printStackTrace()
     */
    public function format(Throwable $e): string;
}
