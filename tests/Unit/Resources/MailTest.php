<?php

declare(strict_types=1);

use SmartDato\IspConfig\Connectors\FakeConnector;
use SmartDato\IspConfig\IspConfig;
use SmartDato\IspConfig\Resources\Mail\Alias;
use SmartDato\IspConfig\Resources\Mail\AliasDomain;
use SmartDato\IspConfig\Resources\Mail\Blacklist;
use SmartDato\IspConfig\Resources\Mail\Catchall;
use SmartDato\IspConfig\Resources\Mail\Domain;
use SmartDato\IspConfig\Resources\Mail\Fetchmail;
use SmartDato\IspConfig\Resources\Mail\Filter;
use SmartDato\IspConfig\Resources\Mail\Forward;
use SmartDato\IspConfig\Resources\Mail\Policy;
use SmartDato\IspConfig\Resources\Mail\RelayRecipient;
use SmartDato\IspConfig\Resources\Mail\SpamfilterBlacklist;
use SmartDato\IspConfig\Resources\Mail\SpamfilterUser;
use SmartDato\IspConfig\Resources\Mail\SpamfilterWhitelist;
use SmartDato\IspConfig\Resources\Mail\Transport;
use SmartDato\IspConfig\Resources\Mail\User;
use SmartDato\IspConfig\Resources\Mail\UserFilter;
use SmartDato\IspConfig\Resources\Mail\Whitelist;

function mailSetup(): array
{
    $fake = new FakeConnector;
    $fake->stub('login', 'session-id');

    $client = new IspConfig($fake, 'admin', 'secret');

    return [$client->mail(), $fake];
}

it('returns all sub-resources', function (): void {
    [$mail] = mailSetup();

    expect($mail->domain())->toBeInstanceOf(Domain::class);
    expect($mail->user())->toBeInstanceOf(User::class);
    expect($mail->alias())->toBeInstanceOf(Alias::class);
    expect($mail->forward())->toBeInstanceOf(Forward::class);
    expect($mail->aliasDomain())->toBeInstanceOf(AliasDomain::class);
    expect($mail->filter())->toBeInstanceOf(Filter::class);
    expect($mail->userFilter())->toBeInstanceOf(UserFilter::class);
    expect($mail->catchall())->toBeInstanceOf(Catchall::class);
    expect($mail->fetchmail())->toBeInstanceOf(Fetchmail::class);
    expect($mail->transport())->toBeInstanceOf(Transport::class);
    expect($mail->relayRecipient())->toBeInstanceOf(RelayRecipient::class);
    expect($mail->policy())->toBeInstanceOf(Policy::class);
    expect($mail->blacklist())->toBeInstanceOf(Blacklist::class);
    expect($mail->whitelist())->toBeInstanceOf(Whitelist::class);
    expect($mail->spamfilterUser())->toBeInstanceOf(SpamfilterUser::class);
    expect($mail->spamfilterBlacklist())->toBeInstanceOf(SpamfilterBlacklist::class);
    expect($mail->spamfilterWhitelist())->toBeInstanceOf(SpamfilterWhitelist::class);
});

it('calls mail_user_add', function (): void {
    [$mail, $fake] = mailSetup();

    $fake->stub('mail_user_add', 15);

    $result = $mail->user()->add(1, ['email' => 'joe@test.com', 'password' => 'secret']);

    expect($result)->toBe(15);

    $fake->assertCalled('mail_user_add');
});

it('calls mail_domain_get_by_domain', function (): void {
    [$mail, $fake] = mailSetup();

    $fake->stub('mail_domain_get_by_domain', ['domain_id' => 3, 'domain' => 'test.com']);

    $result = $mail->domain()->getByDomain('test.com');

    expect($result)->toBe(['domain_id' => 3, 'domain' => 'test.com']);
});

it('calls mail_forward_add', function (): void {
    [$mail, $fake] = mailSetup();

    $fake->stub('mail_forward_add', 8);

    $result = $mail->forward()->add(1, ['source' => 'info@test.com', 'destination' => 'joe@test.com']);

    expect($result)->toBe(8);

    $fake->assertCalled('mail_forward_add');
});

it('calls mail_alias_delete', function (): void {
    [$mail, $fake] = mailSetup();

    $fake->stub('mail_alias_delete', 1);

    $result = $mail->alias()->delete(5);

    expect($result)->toBe(1);

    $fake->assertCalled('mail_alias_delete');
});
