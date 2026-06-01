# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.0.0 - 2026-06-01

First tagged release.

### Added

- `ViteClient` hook provider — injects the Vite dev-server `@vite/client` module script into
  `wp_head` / block-editor `admin_head` when the dev server is hot on a local/development environment.
  Wired by `ConfigProvider` and `ViteClientFactory` from the `vite_client` config key.

### Changed

- **BC:** the hook provider class is renamed `Vite` → **`ViteClient`** (it was already referenced as
  `ViteClient` by `ConfigProvider`/`ViteClientFactory` — the old class name was a latent bug that made
  the package unusable).
- **BC:** adopted the `kaiseki/wp-hook` 2.0 API (`HookProviderInterface` + `addHooks()`), and
  `kaiseki/config` 2.0 (`Config::fromContainer()` + the `.` key delimiter — `vite_client.host` etc.).
- PHP requirement is `^8.2` (PHP 8.4 is the primary target); `thecodingmachine/safe` bumped to `^2.0`.
- **Declared previously-implicit runtime dependencies**: `kaiseki/config ^2.0` (used by the factory)
  and `oscarotero/env ^2.1` (provides `Env\env()`); both worked before only transitively/via the host app.
- Converted the toolchain from PHP_CodeSniffer to the shared `kaiseki/php-coding-standard` php-cs-fixer
  config; PHPStan 2 / PHPUnit 11 / composer-require-checker 4; added the reusable-workflow CI caller,
  `dependabot.yml` and `update-changelog.yml` (the repo had no `.github/` at all).

### Fixed

- `getServerUrl()` now falls back to the configured `vite_client.host`/`port` when the `VITE_HOST`/
  `VITE_PORT` env vars are unset (the constructor values were previously stored but never read), and
  uses explicit (non-short) ternaries to satisfy PHPStan strict rules. The `VITE_*` env vars still take
  precedence, so existing setups are unaffected.
