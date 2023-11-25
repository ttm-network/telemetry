<?php

declare(strict_types=1);

namespace Yiisoft\Telemetry;

/**
 * @internal
 */
final class NullTracer extends AbstractTracer
{
    public function trace(
        string $name,
        callable $callback,
        array $attributes = [],
        bool $scoped = false,
        ?TraceKind $traceKind = null,
        ?int $startTime = null
    ): mixed {
        $span = new Span($name);
        $span->setAttributes($attributes);

        return $this->runScope($span, $callback);
    }

    public function getContext(): array
    {
        return [];
    }

    public function startSpan(string $name, array $attributes = []): SpanInterface
    {
        return new Span($name, $attributes);
    }

    public function endSpan(SpanInterface $span): void
    {
    }
}
