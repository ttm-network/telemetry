<?php

declare(strict_types=1);

namespace TTM\Telemetry;

interface TracerInterface
{
    /**
     * Get current tracer context
     */
    public function getContext(): array;

    public function startSpan(
        string $name,
        array $attributes = [],
        ?TraceKind $traceKind = null,
        ?int $startTime = null
    ): SpanInterface;

    public function endSpan(SpanInterface $span): void;

    public function trace(
        string $name,
        callable $callback,
        array $attributes = [],
        bool $scoped = false,
        ?TraceKind $traceKind = null,
        ?int $startTime = null
    ): mixed;
}
