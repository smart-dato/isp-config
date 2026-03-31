<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Resources;

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

final class Mail extends Resource
{
    public function domain(): Domain
    {
        return new Domain($this->ispConfig);
    }

    public function user(): User
    {
        return new User($this->ispConfig);
    }

    public function alias(): Alias
    {
        return new Alias($this->ispConfig);
    }

    public function forward(): Forward
    {
        return new Forward($this->ispConfig);
    }

    public function aliasDomain(): AliasDomain
    {
        return new AliasDomain($this->ispConfig);
    }

    public function filter(): Filter
    {
        return new Filter($this->ispConfig);
    }

    public function userFilter(): UserFilter
    {
        return new UserFilter($this->ispConfig);
    }

    public function catchall(): Catchall
    {
        return new Catchall($this->ispConfig);
    }

    public function fetchmail(): Fetchmail
    {
        return new Fetchmail($this->ispConfig);
    }

    public function transport(): Transport
    {
        return new Transport($this->ispConfig);
    }

    public function relayRecipient(): RelayRecipient
    {
        return new RelayRecipient($this->ispConfig);
    }

    public function policy(): Policy
    {
        return new Policy($this->ispConfig);
    }

    public function blacklist(): Blacklist
    {
        return new Blacklist($this->ispConfig);
    }

    public function whitelist(): Whitelist
    {
        return new Whitelist($this->ispConfig);
    }

    public function spamfilterUser(): SpamfilterUser
    {
        return new SpamfilterUser($this->ispConfig);
    }

    public function spamfilterBlacklist(): SpamfilterBlacklist
    {
        return new SpamfilterBlacklist($this->ispConfig);
    }

    public function spamfilterWhitelist(): SpamfilterWhitelist
    {
        return new SpamfilterWhitelist($this->ispConfig);
    }
}
