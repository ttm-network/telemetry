<?php

declare(strict_types=1);

namespace TTM\Telemetry\Context;

final class NullContextExtractor implements ContextExtractorInterface
{
    public function extract(array $data): Context
    {
        return new Context();
    }
}
