<?php

declare(strict_types=1);

use SmartDato\IspConfig\Connectors\FakeConnector;

it('returns true by default for unstubbed methods', function (): void {
    $fake = new FakeConnector;

    expect($fake->call('some_method'))->toBeTrue();
});

it('returns stubbed response', function (): void {
    $fake = new FakeConnector;
    $fake->stub('client_get', ['id' => 1, 'name' => 'test']);

    $result = $fake->call('client_get', [1]);

    expect($result)->toBe(['id' => 1, 'name' => 'test']);
});

it('returns same stub on repeated calls', function (): void {
    $fake = new FakeConnector;
    $fake->stub('client_get', ['id' => 1]);

    expect($fake->call('client_get', [1]))->toBe(['id' => 1]);
    expect($fake->call('client_get', [1]))->toBe(['id' => 1]);
});

it('cycles through multiple stubs', function (): void {
    $fake = new FakeConnector;
    $fake->stub('client_get', ['id' => 1]);
    $fake->stub('client_get', ['id' => 2]);

    expect($fake->call('client_get', [1]))->toBe(['id' => 1]);
    expect($fake->call('client_get', [2]))->toBe(['id' => 2]);
});

it('records calls', function (): void {
    $fake = new FakeConnector;

    $fake->call('client_get', [1]);
    $fake->call('client_add', [0, ['name' => 'test']]);

    $recorded = $fake->recorded();

    expect($recorded)->toHaveCount(2);
    expect($recorded[0]['method'])->toBe('client_get');
    expect($recorded[1]['method'])->toBe('client_add');
});

it('records calls filtered by method', function (): void {
    $fake = new FakeConnector;

    $fake->call('client_get', [1]);
    $fake->call('client_add', [0, ['name' => 'test']]);

    expect($fake->recorded('client_get'))->toHaveCount(1);
    expect($fake->recorded('client_add'))->toHaveCount(1);
    expect($fake->recorded('client_delete'))->toHaveCount(0);
});

it('asserts method was called', function (): void {
    $fake = new FakeConnector;

    $fake->call('client_get', [1]);

    $fake->assertCalled('client_get');
});

it('asserts method was not called', function (): void {
    $fake = new FakeConnector;

    $fake->assertNotCalled('client_get');
});

it('asserts call count', function (): void {
    $fake = new FakeConnector;

    $fake->call('client_get', [1]);
    $fake->call('client_get', [2]);

    $fake->assertCallCount('client_get', 2);
});

it('asserts called with matching params', function (): void {
    $fake = new FakeConnector;

    $fake->call('client_get', ['session-id', 42]);

    $fake->assertCalled('client_get', fn (array $params): bool => $params[1] === 42);
});

it('supports fluent stubbing', function (): void {
    $fake = new FakeConnector;

    $result = $fake->stub('login', 'session-123');

    expect($result)->toBeInstanceOf(FakeConnector::class);
});
