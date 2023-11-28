<?php

declare(strict_types=1);

namespace TTM\Telemetry;

final class SpanLink
{
    public function __construct(public readonly mixed $span, public readonly mixed $scope)
    {
    }
}
