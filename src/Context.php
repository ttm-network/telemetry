<?php

declare(strict_types=1);

namespace TTM\Telemetry;

final class Context
{
    public function __construct(private array $context = [])
    {
    }

    public function current(): array
    {
        return $this->context;
    }

    public function setContext(array $ctx): void
    {
        $this->context = $ctx;
    }

    public function resetContext(): void
    {
        $this->context = [];
    }
}
