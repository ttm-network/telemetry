<?php

declare(strict_types=1);

namespace TTM\Telemetry;

final class Event implements EventInterface
{
    public function __construct(
        private readonly string $name,
        private readonly int $timestamp,
        private readonly array $attributes
    ) {
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEpochNanos(): int
    {
        return $this->timestamp;
    }
}
