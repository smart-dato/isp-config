# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is **isp-config** — a Laravel package (`smart-dato/isp-config`) providing an ISPConfig SOAP API SDK for Laravel. Built with Spatie Laravel Package Tools.

- **Namespace:** `SmartDato\IspConfig`
- **PHP:** 8.4+
- **Laravel:** 11, 12, 13
- **Testing:** Orchestra Testbench + Pest 4

## Commands

```bash
# Run tests
composer test

# Run tests with coverage
composer test-coverage

# Run static analysis (PHPStan level 5)
composer analyse

# Format code (Laravel Pint)
composer format

# Run a specific test file
vendor/bin/pest tests/Unit/IspConfigTest.php

# Run a specific test by name
vendor/bin/pest --filter='test name here'
```

## Architecture

Three-layer design wrapping ISPConfig's SOAP API (200+ methods):

```
Facade / IspConfig::make([...]) → IspConfig (client + session mgmt) → Connector (SOAP transport)
                                       ↓
                                  Resources (grouped API methods)
```

### Connectors (`src/Connectors/`)
- **`Connector`** — interface with single `call(string $method, array $params): mixed`
- **`SoapConnector`** — real `\SoapClient` wrapper with lazy init, SSL context, timeout
- **`FakeConnector`** — test double: stub responses, record calls, assertion methods (`assertCalled`, `assertNotCalled`, `assertCallCount`)

### Core Client (`src/IspConfig.php`)
- Constructor takes `Connector`, `username`, `password`
- **`IspConfig::make(array $config)`** — static factory for ad-hoc connections without config file
- **Auto-login** — `call()` lazily authenticates on first API call, caches session
- **Auto-logout** — via Laravel's `terminating` callback
- **Session re-auth** — detects expired sessions and re-authenticates automatically
- Resource accessors: `client()`, `server()`, `sites()`, `mail()`, `dns()`, `database()`, `ftp()`, `shell()`, `cron()`

### Resources (`src/Resources/`)
One class per API category, with sub-resources for nested entities:

- **`Client`** — `client_*` methods (add, get, getAll, update, delete, changePassword, etc.)
- **`Server`** — `server_*` methods (get, getAll, getPhpVersions, IPs, Fail2Ban)
- **`Sites`** → sub-resources: `webDomain()`, `subdomain()`, `aliasDomain()`, `vhostSubdomain()`, `vhostAliasDomain()`, `folder()`, `folderUser()`
- **`Mail`** → sub-resources: `domain()`, `user()`, `alias()`, `forward()`, `aliasDomain()`, `filter()`, `userFilter()`, `catchall()`, `fetchmail()`, `transport()`, `relayRecipient()`, `policy()`, `blacklist()`, `whitelist()`, `spamfilterUser()`, `spamfilterBlacklist()`, `spamfilterWhitelist()`
- **`Dns`** → sub-resources: `zone()`, `a()`, `aaaa()`, `cname()`, `mx()`, `ns()`, `txt()`, `srv()`, `ptr()`, `alias()`, `hinfo()`, `rp()` — DNS records use a parameterized `Record` class
- **`Database`** → sub-resource: `user()`
- **`Ftp`**, **`Shell`**, **`Cron`** — flat CRUD resources

### Exceptions (`src/Exceptions/`)
- `IspConfigException` (base) → `AuthenticationException`, `ConnectionException`, `ApiException`

### Service Provider (`src/IspConfigServiceProvider.php`)
- Registers `IspConfig` as singleton from config values
- Auto-logout on request termination

### Facade (`src/Facades/IspConfig.php`)
- `IspConfig::fake()` — swaps connector with `FakeConnector` (mirrors `Http::fake()` pattern)

## Config

`config/isp-config.php` — keys: `host`, `port`, `username`, `password`, `verify_ssl`, `timeout`

## CI Pipeline

GitHub Actions runs on PHP file changes:
- **Tests** (`run-tests.yml`): Matrix of PHP 8.3/8.4 × Laravel 12/13 × Ubuntu/Windows
- **PHPStan** (`phpstan.yml`): Static analysis on PHP 8.4
- **Pint** (`fix-php-code-style-issues.yml`): Auto-fixes and commits code style changes

## Key Conventions

- All API parameters use `array<string, mixed>` — keys mirror ISPConfig database column names
- Package auto-discovery configured in `composer.json` `extra.laravel`
- Config publishable via `php artisan vendor:publish --tag="isp-config-config"`
- All concrete classes are `final` (except abstract `Resource` base, `IspConfigException` base, and `Facade`)
- `declare(strict_types=1)` in every PHP file
- Architecture tests enforce: strict types, final classes, no debug functions, inheritance rules
