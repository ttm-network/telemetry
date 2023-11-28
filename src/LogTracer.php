<?php

declare(strict_types=1);

namespace TTM\Telemetry;

use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidFactoryInterface;
use Yiisoft\Injector\Injector;

/**
 * @internal
 */
final class LogTracer extends AbstractTracer
{
    private array $context = [];

    public function __construct(
        Injector $injector,
        private readonly ClockInterface $clock,
        private readonly LoggerInterface $logger,
        private readonly UuidFactoryInterface $uuidFactory,
        private readonly StackTraceFormatterInterface $stackTraceFormatter,
    ) {
        parent::__construct($injector);
    }

    public function getContext(): array
    {
        return $this->context;
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
            stackTraceFormatter: $this->stackTraceFormatter,
            startEpochNanos: $startTime ?? $this->clock->now(),
            attributes: $attributes
        );

        $this->context['telemetry'] = $this->uuidFactory->uuid4()->toString();
        $this->spans->add($span);

        return $span;
    }

    public function endSpan(SpanInterface $span): void
    {
        $elapsed = $this->clock->now() - $span->getStartEpochNanos();

        $this->log($span, false, $span->getKind(), $elapsed);
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
            stackTraceFormatter: $this->stackTraceFormatter,
            attributes: $attributes
        );

        $this->context['telemetry'] = $this->uuidFactory->uuid4()->toString();

        $startTime ??= $this->clock->now();

        $result = $this->runScope($span, $callback);

        $elapsed = $this->clock->now() - $startTime;

        $this->log($span, $scoped, $traceKind, $elapsed);

        return $result;
    }

    private function log(SpanInterface $span, bool $scoped, TraceKind $traceKind, int $elapsed): void
    {
        $this->logger->debug(\sprintf('Trace [%s] - [%01.4f ms.]', $span->getName(), $elapsed / 1_000_000), [
            'id' => $this->context['telemetry'],
            'trace_kind' => $traceKind,
            'scoped' => $scoped,
            'status' => $span->getStatus(),
            'attributes' => $span->getAttributes(),
            'events' => $span->getEvents(),
            'elapsed' => $elapsed,
        ]);
    }
}
