<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ViteClient;

use Kaiseki\WordPress\Environment\Environment;
use Kaiseki\WordPress\Environment\StaticEnvironment;
use Kaiseki\WordPress\Hook\HookCallbackProviderInterface;

use function Env\env;
use function function_exists;
use function in_array;
use function is_array;

final class Vite implements HookCallbackProviderInterface
{
    private const VITE_CLIENT = '@vite/client';

    public function __construct(
        private readonly string $host = 'localhost',
        private readonly int $port = 5173,
    )
    {
    }

    public function registerHookCallbacks(): void
    {
        add_action('wp_head', [$this, 'renderViteClientScript']);
        add_action('admin_head', [$this, 'renderViteClientScript']);
    }

    public function renderViteClientScript(): void
    {
        if (!self::isHot() || (is_admin() && !$this->isBlockEditor())) {
            return;
        }

        echo \Safe\sprintf(
            '<script type="module" src="%s%s"></script>',
            trailingslashit($this->getServerUrl()),
            self::VITE_CLIENT
        );
    }

    public function getServerUrl(): string
    {
        return \Safe\sprintf(
            'http://%s:%s/',
            env('VITE_HOST') ?: 'localhost',
            env('VITE_PORT') ?: '5173',
        );
    }

    public function isHot(): bool
    {
        if (!StaticEnvironment::isLocal() && !StaticEnvironment::isDevelopment()) {
            return false;
        }
        $response = wp_remote_get(trailingslashit(self::getServerUrl()) . self::VITE_CLIENT);
        return is_array($response) && $response['response']['code'] === 200;
    }

    private function isBlockEditor(): bool
    {
        if (!is_admin() || !function_exists('get_current_screen')) {
            return false;
        }

        return (bool)get_current_screen()?->is_block_editor();
    }
}
