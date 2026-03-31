<?php

declare(strict_types=1);

use SmartDato\IspConfig\Connectors\FakeConnector;
use SmartDato\IspConfig\IspConfig;
use SmartDato\IspConfig\Resources\Dns\Record;
use SmartDato\IspConfig\Resources\Dns\Zone;

function dnsSetup(): array
{
    $fake = new FakeConnector;
    $fake->stub('login', 'session-id');

    $client = new IspConfig($fake, 'admin', 'secret');

    return [$client->dns(), $fake];
}

it('returns zone sub-resource', function (): void {
    [$dns] = dnsSetup();

    expect($dns->zone())->toBeInstanceOf(Zone::class);
});

it('returns parameterized record sub-resources', function (): void {
    [$dns] = dnsSetup();

    expect($dns->a())->toBeInstanceOf(Record::class);
    expect($dns->aaaa())->toBeInstanceOf(Record::class);
    expect($dns->cname())->toBeInstanceOf(Record::class);
    expect($dns->mx())->toBeInstanceOf(Record::class);
    expect($dns->ns())->toBeInstanceOf(Record::class);
    expect($dns->txt())->toBeInstanceOf(Record::class);
    expect($dns->srv())->toBeInstanceOf(Record::class);
    expect($dns->ptr())->toBeInstanceOf(Record::class);
});

it('calls dns_a_add via record', function (): void {
    [$dns, $fake] = dnsSetup();

    $fake->stub('dns_a_add', 10);

    $result = $dns->a()->add(1, ['zone' => 5, 'name' => 'www', 'data' => '1.2.3.4']);

    expect($result)->toBe(10);

    $fake->assertCalled('dns_a_add');
});

it('calls dns_txt_get via record', function (): void {
    [$dns, $fake] = dnsSetup();

    $fake->stub('dns_txt_get', ['id' => 3, 'data' => 'v=spf1']);

    $result = $dns->txt()->get(3);

    expect($result)->toBe(['id' => 3, 'data' => 'v=spf1']);
});

it('calls dns_cname_update via record', function (): void {
    [$dns, $fake] = dnsSetup();

    $fake->stub('dns_cname_update', 1);

    $result = $dns->cname()->update(1, 5, ['name' => 'mail', 'data' => 'mail.example.com.']);

    expect($result)->toBe(1);

    $fake->assertCalled('dns_cname_update');
});

it('calls dns_mx_delete via record', function (): void {
    [$dns, $fake] = dnsSetup();

    $fake->stub('dns_mx_delete', 1);

    $result = $dns->mx()->delete(7);

    expect($result)->toBe(1);

    $fake->assertCalled('dns_mx_delete');
});

it('calls dns_zone_add', function (): void {
    [$dns, $fake] = dnsSetup();

    $fake->stub('dns_zone_add', 20);

    $result = $dns->zone()->add(1, ['origin' => 'test.com.', 'ns' => 'ns1.test.com.']);

    expect($result)->toBe(20);
});

it('gets all records by zone', function (): void {
    [$dns, $fake] = dnsSetup();

    $fake->stub('dns_rr_get_all_by_zone', [['id' => 1], ['id' => 2]]);

    $result = $dns->getAllByZone(5);

    expect($result)->toHaveCount(2);
});
