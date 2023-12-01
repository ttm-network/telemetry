<?php

declare(strict_types=1);

namespace TTM\Telemetry\Collection;

use TTM\Telemetry\SpanInterface;
use TTM\Telemetry\SpanLink;

/**
 * @interal
 */
final class SpanCollection
{
    /**
     * @var ItemBag[]
     */
    private array $items = [];

    public function add(SpanInterface $span, ?SpanLink $link = null): void
    {
        $this->items[$this->getItemId($span)] = new ItemBag($span, $link);
    }

    public function remove(SpanInterface $span): void
    {
        unset($this->items[$this->getItemId($span)]);
    }

    public function last(): ?ItemBag
    {
        return $this->items[array_key_last($this->items)] ?? null;
    }

    public function link(SpanInterface $span): ?SpanLink
    {
        return $this->items[$this->getItemId($span)]?->link;
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
