<?php

declare(strict_types=1);

namespace TTM\Telemetry;

use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidFactory;
use Yiisoft\Injector\Injector;

/**
 * @internal The component is under development.
 */
final class LogTracerFactory implements TracerFactoryInterface
{
    public function __construct(
        private readonly Injector $injector,
        private readonly ClockInterface $clock,
        private readonly StackTraceFormatterInterface $stackTraceFormatter,
        private readonly LoggerInterface $logger
    ) {
    }

    public function make(array $context = []): TracerInterface
    {
        return new LogTracer(
            injector: $this->injector,
            clock: $this->clock,
            logger: $this->logger,
            uuidFactory: new UuidFactory(),
            stackTraceFormatter: $this->stackTraceFormatter
        );
    }
}
