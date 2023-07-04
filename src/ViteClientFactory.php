<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ViteClient;

use Kaiseki\Config\Config;
use Psr\Container\ContainerInterface;

final class ViteClientFactory
{
    public function __invoke(ContainerInterface $container): ViteClient
    {
        $config = Config::get($container);
        return new ViteClient(
            $config->string('vite_client/host', 'localhost'),
            $config->int('vite_client/port', 5173),
        );
    }
}
