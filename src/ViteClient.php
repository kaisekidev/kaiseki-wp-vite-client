<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ViteClient;

use Kaiseki\WordPress\Environment\StaticEnvironment;
use Kaiseki\WordPress\Hook\HookProviderInterface;

use function add_action;
use function Env\env;
use function function_exists;
use function get_current_screen;
use function is_admin;
use function is_array;
use function is_numeric;
use function is_string;
use function trailingslashit;
use function wp_remote_get;

final class ViteClient implements HookProviderInterface
{
    private const VITE_CLIENT = '@vite/client';

    public function __construct(
        private readonly string $host = 'localhost',
        private readonly int $port = 5173,
    ) {
    }

    public function addHooks(): void
    {
        add_action('wp_head', [$this, 'renderViteClientScript']);
        add_action('admin_head', [$this, 'renderViteClientScript']);
    }

    public function renderViteClientScript(): void
    {
        if (!$this->isHot() || (is_admin() && !$this->isBlockEditor())) {
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
        $host = env('VITE_HOST');
        $port = env('VITE_PORT');

        return \Safe\sprintf(
            'http://%s:%s/',
            is_string($host) && $host !== '' ? $host : $this->host,
            is_numeric($port) ? $port : $this->port,
        );
    }

    public function isHot(): bool
    {
        if (!StaticEnvironment::isLocal() && !StaticEnvironment::isDevelopment()) {
            return false;
        }
        $response = wp_remote_get(trailingslashit($this->getServerUrl()) . self::VITE_CLIENT);
        if (!is_array($response)) {
            return false;
        }
        $responseData = $response['response'] ?? null;

        return is_array($responseData) && ($responseData['code'] ?? null) === 200;
    }

    private function isBlockEditor(): bool
    {
        if (!is_admin() || !function_exists('get_current_screen')) {
            return false;
        }

        return (bool)get_current_screen()?->is_block_editor();
    }
}
