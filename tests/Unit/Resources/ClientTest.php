<?php

declare(strict_types=1);

use SmartDato\IspConfig\Connectors\FakeConnector;
use SmartDato\IspConfig\IspConfig;

function clientSetup(): array
{
    $fake = new FakeConnector;
    $fake->stub('login', 'session-id');

    $client = new IspConfig($fake, 'admin', 'secret');

    return [$client->client(), $fake];
}

it('calls client_add with reseller_id and params', function (): void {
    [$resource, $fake] = clientSetup();

    $fake->stub('client_add', 5);

    $result = $resource->add(0, ['company_name' => 'Acme']);

    expect($result)->toBe(5);

    $fake->assertCalled('client_add', fn (array $params): bool => $params[0] === 'session-id'
        && $params[1] === 0
        && $params[2] === ['company_name' => 'Acme']);
});

it('calls client_get', function (): void {
    [$resource, $fake] = clientSetup();

    $fake->stub('client_get', ['id' => 1, 'company_name' => 'Acme']);

    $result = $resource->get(1);

    expect($result)->toBe(['id' => 1, 'company_name' => 'Acme']);
});

it('calls client_get_all', function (): void {
    [$resource, $fake] = clientSetup();

    $fake->stub('client_get_all', [['id' => 1], ['id' => 2]]);

    $result = $resource->getAll();

    expect($result)->toHaveCount(2);
});

it('calls client_update', function (): void {
    [$resource, $fake] = clientSetup();

    $fake->stub('client_update', 1);

    $result = $resource->update(1, 0, ['company_name' => 'Updated']);

    expect($result)->toBe(1);
});

it('calls client_delete', function (): void {
    [$resource, $fake] = clientSetup();

    $fake->stub('client_delete', 1);

    $result = $resource->delete(1);

    expect($result)->toBe(1);

    $fake->assertCalled('client_delete');
});

it('calls client_change_password', function (): void {
    [$resource, $fake] = clientSetup();

    $fake->stub('client_change_password', 1);

    $result = $resource->changePassword(1, 'new-password');

    expect($result)->toBe(1);
});

it('calls client_get_by_username', function (): void {
    [$resource, $fake] = clientSetup();

    $fake->stub('client_get_by_username', ['id' => 1, 'username' => 'guy']);

    $result = $resource->getByUsername('guy');

    expect($result)->toBe(['id' => 1, 'username' => 'guy']);
});
