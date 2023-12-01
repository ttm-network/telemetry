<?php

declare(strict_types=1);

namespace TTM\Telemetry\Context;

interface ContextExtractorInterface
{
    public function extract(array $data): array;
}
