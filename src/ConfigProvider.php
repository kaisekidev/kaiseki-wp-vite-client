<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ViteClient;

final class ConfigProvider
{
    /**
     * @return array<mixed>
     */
    public function __invoke(): array
    {
        return [
            'hook' => [
                'provider' => [
                    ViteClient::class,
                ]
            ],
            'dependencies' => [
                'aliases' => [],
                'factories' => [
                    ViteClient::class => ViteClientFactory::class,
                ],
            ],
        ];
    }
}
