<?php

declare(strict_types=1);

namespace TTM\Telemetry\Context;

use Psr\Container\ContainerInterface;
use TTM\Telemetry\Exception\InvalidArgumentException;

final class ContextExtractFactory
{
    public function __construct(
        private readonly string $default,
        /** @var string[] */
        private readonly array $extractors,
        private readonly ContainerInterface $container
    ) {
    }

    public function create(?string $name = null): ContextExtractorInterface
    {
        $name ??= $this->default;

        if (isset($this->extractors[$name])) {
            return $this->container->get($this->extractors[$name]);
        }

        throw new InvalidArgumentException(
            \sprintf('Config for context extractor `%s` is not defined.', $name)
        );
    }
}
