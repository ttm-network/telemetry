<?php

declare(strict_types=1);

namespace Yiisoft\Telemetry;

use Yiisoft\Injector\Injector;

/**
 * @internal The component is under development.
 */
abstract class AbstractTracer implements TracerInterface
{
    public function __construct(
        private readonly ?Injector $injector,
    ) {
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
}
