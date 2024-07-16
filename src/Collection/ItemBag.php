<?php

declare(strict_types=1);

namespace TTM\Telemetry\Collection;

use TTM\Telemetry\SpanInterface;
use TTM\Telemetry\SpanLink;

final class ItemBag
{
    public function __construct(public readonly SpanInterface $span, public readonly ?SpanLink $link = null)
    {
    }
}
