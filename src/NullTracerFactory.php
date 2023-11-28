<?php

declare(strict_types=1);

namespace TTM\Telemetry;

use Yiisoft\Injector\Injector;

/**
 * @internal
 */
final class NullTracerFactory implements TracerFactoryInterface
{
    public function __construct(
        private readonly Injector $injector,
        private readonly ClockInterface $clock,
        private readonly StackTraceFormatterInterface $stackTraceFormatter,
    ) {
    }

    public function make(array $context = []): TracerInterface
    {
        return new NullTracer(
            injector: $this->injector,
            clock: $this->clock,
            stackTraceFormatter: $this->stackTraceFormatter
        );
    }
}
