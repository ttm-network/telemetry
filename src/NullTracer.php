<?php

declare(strict_types=1);

namespace TTM\Telemetry;

use Yiisoft\Injector\Injector;

/**
 * @internal
 */
final class NullTracer extends AbstractTracer
{
    public function __construct(
        Injector $injector,
        Context $context,
        private readonly ClockInterface $clock,
        private readonly StackTraceFormatterInterface $stackTraceFormatter,
    ) {
        parent::__construct($injector, $context);
    }

    public function startSpan(
        string $name,
        array $attributes = [],
        bool $scoped = false,
        ?TraceKind $traceKind = null,
        ?int $startTime = null
    ): SpanInterface {
        $span = new Span(
            name: $name,
            traceKind: $traceKind ?? TraceKind::INTERNAL,
            clock: $this->clock,
            stackTraceFormatter: $this->stackTraceFormatter
        );

        $this->spans->add($span);

        return $span;
    }

    public function endSpan(SpanInterface $span): void
    {
        $this->spans->remove($span);
    }

    public function trace(
        string $name,
        callable $callback,
        array $attributes = [],
        bool $scoped = false,
        ?TraceKind $traceKind = null,
        ?int $startTime = null
    ): mixed {
        $span = new Span(
            name: $name,
            traceKind: $traceKind ?? TraceKind::INTERNAL,
            clock: $this->clock,
            stackTraceFormatter: $this->stackTraceFormatter
        );
        $span->setAttributes($attributes);

        return $this->runScope($span, $callback);
    }
}
