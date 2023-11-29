<?php

declare(strict_types=1);

namespace TTM\Telemetry;

use Throwable;
use TTM\Telemetry\Span\Status;

interface SpanInterface
{
    /**
     * Get the current span name.
     *
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * Update the current span name.
     *
     * @param non-empty-string $name
     */
    public function updateName(string $name): self;

    /**
     * Set a status for the current span.
     *
     * @param non-empty-string|int $code
     * @param non-empty-string|null $description
     */
    public function setStatus(string|int $code, string $description = null): self;

    /**
     * Get the current span status.
     */
    public function getStatus(): ?Status;

    /**
     * Get the current span attributes.
     */
    public function getAttributes(): array;

    /**
     * Set the current span attributes.
     */
    public function setAttributes(array $attributes): self;

    public function getKind(): TraceKind;

    public function getStartEpochNanos(): int;

    public function getLinks(): array;

    /**
     * Set the current span attribute.
     *
     * @param non-empty-string $name
     */
    public function setAttribute(string $name, mixed $value): self;

    /**
     * Add attributes to the current span. If the attribute key already exists, it will be overwritten.
     */
    public function addAttributes(array $attributes): self;

    /**
     * Add event to current span
     */
    public function addEvent(string $name, iterable $attributes = [], int $timestamp = null): SpanInterface;

    /**
     * @return Event[]
     */
    public function getEvents(): array;

    public function recordException(Throwable $exception, iterable $attributes = []): SpanInterface;

    /**
     * Check if the current span has an attribute with given name.
     *
     * @param non-empty-string $name
     */
    public function hasAttribute(string $name): bool;

    /**
     * Set the current span attribute by given name.
     *
     * @param non-empty-string $name
     */
    public function getAttribute(string $name): mixed;
}
