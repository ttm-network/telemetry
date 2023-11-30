<?php

declare(strict_types=1);

namespace TTM\Telemetry;

use Psr\Container\ContainerInterface;
use TTM\Telemetry\Exception\InvalidArgumentException;

final class TracerFactory implements TracerFactoryInterface
{
    public function __construct(
        private readonly string $default,
        /** @var string[] */
        private readonly array $drivers,
        private readonly ContainerInterface $container
    ) {
    }

    public function create(?string $name = null): TracerInterface
    {
        $name ??= $this->default;

        if (isset($this->drivers[$name])) {
            return $this->container->get($this->drivers[$name]);
        }

        throw new InvalidArgumentException(
            \sprintf('Config for telemetry driver `%s` is not defined.', $name)
        );
    }
}
