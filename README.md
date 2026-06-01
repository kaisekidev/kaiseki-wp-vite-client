# kaiseki/wp-vite-client

Inject the Vite dev-server client script into WordPress on local/development environments.

`ViteClient` is a `kaiseki/wp-hook` `HookProviderInterface` that, when the Vite dev server is running
("hot") and the environment is local or development, prints the `@vite/client` module script into
`wp_head` (and the block-editor `admin_head`). It is inert on production and when the dev server isn't
reachable.

## Installation

```bash
composer require kaiseki/wp-vite-client
```

Requires PHP 8.2 or newer.

## Usage

Register `ConfigProvider` with your laminas-style config aggregator and activate `ViteClient` via
`kaiseki/wp-hook`:

```php
use Kaiseki\WordPress\ViteClient\ViteClient;

return [
    'vite_client' => [
        'host' => 'localhost',
        'port' => 5173,
    ],
    'hook' => [
        'provider' => [
            ViteClient::class,
        ],
    ],
];
```

The dev-server host/port come from the `VITE_HOST` / `VITE_PORT` environment variables when set,
otherwise from the `vite_client.host` / `vite_client.port` config (defaulting to `localhost:5173`).
"Hot" detection is a reachability check against the Vite client URL, gated to local/development
environments via `kaiseki/wp-env`.

## Development

```bash
composer install
composer check   # check-deps, cs-check, phpstan
```

## License

MIT — see [LICENSE](LICENSE).
