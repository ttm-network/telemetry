<?php

declare(strict_types=1);

namespace TTM\Telemetry;

use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidFactoryInterface;
use TTM\Telemetry\Exception\EndSpanException;
use Yiisoft\Injector\Injector;

/**
 * @internal
 */
final class LogTracer extends AbstractTracer
{
    private array $context = [];
    private array $spans = [];

    public function __construct(
        Injector $injector,
        private readonly ClockInterface $clock,
        private readonly LoggerInterface $logger,
        private readonly UuidFactoryInterface $uuidFactory
    ) {
        parent::__construct($injector);
    }

    public function trace(
        string $name,
        callable $callback,
        array $attributes = [],
        bool $scoped = false,
        ?TraceKind $traceKind = null,
        ?int $startTime = null
    ): mixed {
        $span = new Span($name, $attributes);

        $this->context['telemetry'] = $this->uuidFactory->uuid4()->toString();

        $startTime ??= $this->clock->now();

        $result = $this->runScope($span, $callback);

        $elapsed = $this->clock->now() - $startTime;

        $this->log($span, $scoped, $traceKind, $elapsed);

        return $result;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function startSpan(
        string $name,
        array $attributes = [],
        ?TraceKind $traceKind = null,
        ?int $startTime = null
    ): SpanInterface {
        $span = new Span($name, $attributes);
        $this->context['telemetry'] = $this->uuidFactory->uuid4()->toString();
        $startTime ??= $this->clock->now();

        $this->spans[spl_object_id($span)] = [$span, $traceKind, $startTime];

        return $span;
    }

    public function endSpan(SpanInterface $span): void
    {
        $spanId = spl_object_id($span);

        if (!isset($spanId)) {
            throw new EndSpanException(
                sprintf(
                    'Not found active span for %s. The span may not have been started or already ended.',
                    $span->getName()
                )
            );
        }

        [$span, $traceKind, $startTime] = $this->spans[$spanId];

        $elapsed = $this->clock->now() - $startTime;

        $this->log($span, false, $traceKind, $elapsed);
    }

    public function endAllSpans(): void
    {
        if ($this->spans === []) {
            return;
        }

        $spans = array_reverse($this->spans);
        array_walk($spans, fn(SpanInterface $span) => $this->endSpan($span));
    }

    private function log(SpanInterface $span, bool $scoped, TraceKind $traceKind, int $elapsed): void
    {
        $this->logger->debug(\sprintf('Trace [%s] - [%01.4f ms.]', $span->getName(), $elapsed / 1_000_000), [
            'attributes' => $span->getAttributes(),
            'status' => $span->getStatus(),
            'scoped' => $scoped,
            'trace_kind' => $traceKind,
            'elapsed' => $elapsed,
            'id' => $this->context['telemetry'],
        ]);
    }
}
