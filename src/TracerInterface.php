<?php

declare(strict_types=1);

namespace TTM\Telemetry;

use TTM\Telemetry\Exception\ActiveSpanUnavailableException;

interface TracerInterface
{
    /**
     * Get current tracer context
     */
    public function getContext(): array;

    /**
     * @throws ActiveSpanUnavailableException
     */
    public function getCurrentSpan(): SpanInterface;

    public function startSpan(
        string $name,
        array $attributes = [],
        bool $scoped = false,
        array $context = [],
        ?TraceKind $traceKind = null,
        ?int $startTime = null
    ): SpanInterface;

    public function endSpan(SpanInterface $span): void;

    public function endAllSpans(): void;

    public function trace(
        string $name,
        callable $callback,
        array $attributes = [],
        bool $scoped = false,
        array $context = [],
        ?TraceKind $traceKind = null,
        ?int $startTime = null
    ): mixed;
}
