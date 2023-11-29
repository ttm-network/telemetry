<?php

declare(strict_types=1);

namespace TTM\Telemetry;

use Throwable;
use TTM\Telemetry\Span\Status;

/**
 * @internal
 */
final class Span implements SpanInterface
{
    private ?Status $status = null;
    private array $events = [];

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        private string $name,
        private readonly TraceKind $traceKind,
        private readonly ClockInterface $clock,
        private readonly StackTraceFormatterInterface $stackTraceFormatter,
        private readonly ?int $startEpochNanos = null,
        private array $attributes = [],
        private readonly array $links = []
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function updateName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getKind(): TraceKind
    {
        return $this->traceKind;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function setAttribute(string $name, mixed $value): self
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function addAttributes(array $attributes): self
    {
        foreach ($attributes as $attribute => $value) {
            $this->setAttribute($attribute, $value);
        }

        return $this;
    }

    public function hasAttribute(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function getAttribute(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    public function getStartEpochNanos(): int
    {
        return $this->startEpochNanos;
    }

    public function setStatus(string|int $code, string $description = null): self
    {
        $this->status = new Status($code, $description);

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function addEvent(string $name, iterable $attributes = [], int $timestamp = null): SpanInterface
    {
        $timestamp ??= $this->clock->now();

        $this->events[] = new Event($name, $timestamp, $attributes);

        return $this;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    public function recordException(Throwable $exception, iterable $attributes = []): SpanInterface
    {
        $timestamp ??= $this->clock->now();
        $eventAttributes = [
            'exception.type' => get_class($exception),
            'exception.message' => $exception->getMessage(),
            'exception.stacktrace' => $this->stackTraceFormatter->format($exception),
        ];

        foreach ($attributes as $key => $value) {
            $eventAttributes[$key] = $value;
        }

        $this->events[] = new Event('exception', $timestamp, $eventAttributes);

        return $this;
    }
}
