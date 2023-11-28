<?php

declare(strict_types=1);

namespace TTM\Telemetry;

final class SpanCollection
{
    private array $items = [];

    public function add(SpanInterface $span): void
    {
        $this->items[$this->getItemId($span)] = $span;
    }

    public function remove(SpanInterface $span): void
    {
        unset($this->items[$this->getItemId($span)]);
    }

    public function last(): ?SpanInterface
    {
        return $this->items[array_key_last($this->items)];
    }

    public function all(): array
    {
        return $this->items;
    }

    private function getItemId(SpanInterface $span): int
    {
        return spl_object_id($span);
    }
}
