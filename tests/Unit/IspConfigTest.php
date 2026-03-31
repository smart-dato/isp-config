<?php

declare(strict_types=1);

use SmartDato\IspConfig\Connectors\FakeConnector;
use SmartDato\IspConfig\Exceptions\ApiException;
use SmartDato\IspConfig\Exceptions\AuthenticationException;
use SmartDato\IspConfig\IspConfig;
use SmartDato\IspConfig\Resources\Client;
use SmartDato\IspConfig\Resources\Cron;
use SmartDato\IspConfig\Resources\Database;
use SmartDato\IspConfig\Resources\Dns;
use SmartDato\IspConfig\Resources\Ftp;
use SmartDato\IspConfig\Resources\Mail;
use SmartDato\IspConfig\Resources\Server;
use SmartDato\IspConfig\Resources\Shell;
use SmartDato\IspConfig\Resources\Sites;

function createClient(?FakeConnector $fake = null): array
{
    $fake ??= new FakeConnector;
    $fake->stub('login', 'test-session-id');

    $client = new IspConfig($fake, 'admin', 'secret');

    return [$client, $fake];
}

it('logs in automatically on first call', function (): void {
    [$client, $fake] = createClient();

    $fake->stub('server_get_all', [['server_id' => 1]]);

    $client->call('server_get_all');

    $fake->assertCalled('login');
});

it('reuses session across calls', function (): void {
    [$client, $fake] = createClient();

    $fake->stub('server_get_all', [['server_id' => 1]]);

    $client->call('server_get_all');
    $client->call('server_get_all');

    $fake->assertCallCount('login', 1);
});

it('throws AuthenticationException on login failure', function (): void {
    $fake = new FakeConnector;
    $fake->stub('login', '');

    $client = new IspConfig($fake, 'admin', 'wrong');

    $client->call('server_get_all');
})->throws(AuthenticationException::class);

it('throws ApiException when API returns false', function (): void {
    [$client, $fake] = createClient();

    $fake->stub('client_get', false);

    $client->call('client_get', 999);
})->throws(ApiException::class);

it('logs out and clears session', function (): void {
    [$client, $fake] = createClient();

    $fake->stub('server_get_all', []);

    $client->call('server_get_all');
    $client->logout();

    $fake->assertCalled('logout');
});

it('does not call logout when not logged in', function (): void {
    [$client, $fake] = createClient();

    $client->logout();

    $fake->assertNotCalled('logout');
});

it('prepends session_id to all calls', function (): void {
    [$client, $fake] = createClient();

    $fake->stub('client_get', ['id' => 1]);

    $client->call('client_get', 42);

    $fake->assertCalled('client_get', fn (array $params): bool => $params[0] === 'test-session-id' && $params[1] === 42);
});

it('creates instance via make factory', function (): void {
    $client = IspConfig::make([
        'host' => '127.0.0.1',
        'username' => 'admin',
        'password' => 'secret',
    ]);

    expect($client)->toBeInstanceOf(IspConfig::class);
});

it('creates instance via make factory with all options', function (): void {
    $client = IspConfig::make([
        'host' => '127.0.0.1',
        'port' => 9090,
        'username' => 'admin',
        'password' => 'secret',
        'verify_ssl' => false,
        'timeout' => 60,
    ]);

    expect($client)->toBeInstanceOf(IspConfig::class);
});

it('returns resource accessors', function (): void {
    [$client] = createClient();

    expect($client->client())->toBeInstanceOf(Client::class);
    expect($client->server())->toBeInstanceOf(Server::class);
    expect($client->sites())->toBeInstanceOf(Sites::class);
    expect($client->mail())->toBeInstanceOf(Mail::class);
    expect($client->dns())->toBeInstanceOf(Dns::class);
    expect($client->database())->toBeInstanceOf(Database::class);
    expect($client->ftp())->toBeInstanceOf(Ftp::class);
    expect($client->shell())->toBeInstanceOf(Shell::class);
    expect($client->cron())->toBeInstanceOf(Cron::class);
});
