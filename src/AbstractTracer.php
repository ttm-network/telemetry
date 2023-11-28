<?php

declare(strict_types=1);

namespace TTM\Telemetry;

use TTM\Telemetry\Exception\TracerException;
use Yiisoft\Injector\Injector;

/**
 * @internal The component is under development.
 */
abstract class AbstractTracer implements TracerInterface
{
    /**
     * @var SpanCollection<SpanInterface>
     */
    protected readonly SpanCollection $spans;

    public function __construct(
        private readonly ?Injector $injector,
    ) {
        $this->spans = new SpanCollection();
    }

    public function getCurrentSpan(): SpanInterface
    {
        if ($this->spans->all() === []) {
            throw new TracerException('No active spans available.');
        }

        return $this->spans->last();
    }

    /**
     * @throws \Throwable
     */
    final protected function runScope(Span $span, callable $callback): mixed
    {
        return $this->injector->invoke($callback, [
            SpanInterface::class => $span,
            TracerInterface::class => $this
        ]);
    }

    public function endAllSpans(): void
    {
        if ($this->spans->all() === []) {
            return;
        }

        $spans = array_reverse($this->spans->all());
        array_walk($spans, fn(SpanInterface $span) => $this->endSpan($span));
    }

    private function getSpanId(SpanInterface $span): int
    {
        return spl_object_id($span);
    }
}
