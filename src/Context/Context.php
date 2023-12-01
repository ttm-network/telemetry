<?php

declare(strict_types=1);

namespace TTM\Telemetry\Context;

final class Context
{
    public function __construct(private array $context = [])
    {
    }

    public function current(): array
    {
        return $this->context;
    }

    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    public function resetContext(): void
    {
        $this->context = [];
    }
}
