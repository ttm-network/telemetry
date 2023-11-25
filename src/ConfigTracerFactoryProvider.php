<?php

declare(strict_types=1);

namespace Yiisoft\Telemetry;

use Psr\Container\ContainerInterface;
use Yiisoft\Telemetry\Exception\InvalidArgumentException;

final class ConfigTracerFactoryProvider implements TracerFactoryProviderInterface
{
    public function __construct(
        private readonly string $default,
        /** @var string[] */
        private readonly array $drivers,
        private readonly ContainerInterface $container
    ) {
    }

    public function getTracerFactory(?string $name = null): TracerFactoryInterface
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
