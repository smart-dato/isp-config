# ISPConfig SDK for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/smart-dato/isp-config.svg?style=flat-square)](https://packagist.org/packages/smart-dato/isp-config)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/smart-dato/isp-config/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/smart-dato/isp-config/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/smart-dato/isp-config/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/smart-dato/isp-config/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/smart-dato/isp-config.svg?style=flat-square)](https://packagist.org/packages/smart-dato/isp-config)

A Laravel SDK for the [ISPConfig](https://www.ispconfig.org/) SOAP API. Provides a fluent, resource-oriented interface to manage clients, websites, mail, DNS, databases, and more.

## Requirements

- PHP 8.4+
- Laravel 11, 12, or 13
- PHP SOAP extension (`ext-soap`)

## Installation

```bash
composer require smart-dato/isp-config
```

Publish the config file:

```bash
php artisan vendor:publish --tag="isp-config-config"
```

Add your ISPConfig credentials to `.env`:

```env
ISPCONFIG_HOST=your-server.example.com
ISPCONFIG_PORT=8080
ISPCONFIG_USERNAME=your-remote-user
ISPCONFIG_PASSWORD=your-password
ISPCONFIG_VERIFY_SSL=true
ISPCONFIG_TIMEOUT=30
```

> **Note:** The remote user must be created in ISPConfig under **System > Remote Users** with the appropriate permission checkboxes enabled.

## Usage

### Via Facade

```php
use SmartDato\IspConfig\Facades\IspConfig;

// Clients
$clients = IspConfig::client()->getAll();
$client = IspConfig::client()->get(1);
$id = IspConfig::client()->add(0, [
    'company_name' => 'Acme Corp',
    'contact_name' => 'John Doe',
    'username' => 'acme',
    'password' => 'secret',
    // ...
]);

// Websites
$siteId = IspConfig::sites()->webDomain()->add(1, [
    'server_id' => 1,
    'domain' => 'example.com',
    'php' => 'php-fpm',
    'active' => 'y',
    // ...
]);

// Mail
$mailUserId = IspConfig::mail()->user()->add(1, [
    'server_id' => 1,
    'email' => 'joe@example.com',
    'password' => 'secret',
    // ...
]);

// DNS
$recordId = IspConfig::dns()->a()->add(1, [
    'server_id' => 1,
    'zone' => 5,
    'name' => 'www',
    'data' => '93.184.216.34',
    'ttl' => '3600',
    'active' => 'y',
]);

// Databases
$dbId = IspConfig::database()->add(1, [
    'server_id' => 1,
    'type' => 'mysql',
    'database_name' => 'mydb',
    'database_user_id' => 1,
    'active' => 'y',
]);
```

### Via Dependency Injection

```php
use SmartDato\IspConfig\IspConfig;

final class ServerController
{
    public function __construct(
        private readonly IspConfig $ispConfig,
    ) {}

    public function index(): array
    {
        return $this->ispConfig->server()->getAll();
    }
}
```

### Ad-hoc Connections

Create a standalone client without relying on config — useful for multi-server setups or dynamic credentials:

```php
use SmartDato\IspConfig\IspConfig;

$isp = IspConfig::make([
    'host' => '192.168.1.100',
    'port' => 8080,
    'username' => 'admin',
    'password' => 'secret',
    'verify_ssl' => false,
    'timeout' => 60,
]);

$clients = $isp->client()->getAll();
```

### Available Resources

| Accessor | Sub-resources | API prefix |
|----------|--------------|------------|
| `client()` | — | `client_*` |
| `server()` | — | `server_*` |
| `sites()` | `webDomain()`, `subdomain()`, `aliasDomain()`, `vhostSubdomain()`, `vhostAliasDomain()`, `folder()`, `folderUser()` | `sites_web_*` |
| `mail()` | `domain()`, `user()`, `alias()`, `forward()`, `aliasDomain()`, `filter()`, `userFilter()`, `catchall()`, `fetchmail()`, `transport()`, `relayRecipient()`, `policy()`, `blacklist()`, `whitelist()`, `spamfilterUser()`, `spamfilterBlacklist()`, `spamfilterWhitelist()` | `mail_*` |
| `dns()` | `zone()`, `a()`, `aaaa()`, `cname()`, `mx()`, `ns()`, `txt()`, `srv()`, `ptr()`, `alias()`, `hinfo()`, `rp()` | `dns_*` |
| `database()` | `user()` | `sites_database_*` |
| `ftp()` | — | `sites_ftp_user_*` |
| `shell()` | — | `sites_shell_user_*` |
| `cron()` | — | `sites_cron_*` |

Most resources provide `add()`, `get()`, `update()`, and `delete()` methods. Parameter array keys mirror the ISPConfig database column names — refer to the [ISPConfig API documentation](https://timmehosting.de/ispconfig-api-schnittstelle-zur-automatisierung) for available fields.

### Session Management

Sessions are managed automatically:

- **Auto-login** — authenticates lazily on the first API call
- **Auto-logout** — logs out when the Laravel request terminates
- **Session re-auth** — detects expired sessions and re-authenticates transparently

### Raw API Calls

For methods not yet wrapped in a resource class:

```php
$result = IspConfig::call('some_api_method', $arg1, $arg2);

// List all available API methods
$methods = IspConfig::getFunctionList();
```

## Testing

The package provides a `FakeConnector` for testing, following Laravel's `Http::fake()` pattern:

```php
use SmartDato\IspConfig\Facades\IspConfig;

$fake = IspConfig::fake();

// Stub responses
$fake->stub('client_get', ['id' => 1, 'company_name' => 'Acme']);
$fake->stub('mail_user_add', 15);

// Call your application code
$client = IspConfig::client()->get(1);
$mailUserId = IspConfig::mail()->user()->add(1, ['email' => 'joe@test.com']);

// Assert calls were made
$fake->assertCalled('client_get');
$fake->assertCalled('mail_user_add', fn (array $params) => $params[2]['email'] === 'joe@test.com');
$fake->assertNotCalled('client_delete');
$fake->assertCallCount('client_get', 1);
```

Run the package test suite:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [SmartDato](https://github.com/smart-dato)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
