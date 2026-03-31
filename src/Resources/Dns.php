<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Resources;

use SmartDato\IspConfig\Resources\Dns\Record;
use SmartDato\IspConfig\Resources\Dns\Zone;

final class Dns extends Resource
{
    public function zone(): Zone
    {
        return new Zone($this->ispConfig);
    }

    public function a(): Record
    {
        return new Record($this->ispConfig, 'a');
    }

    public function aaaa(): Record
    {
        return new Record($this->ispConfig, 'aaaa');
    }

    public function cname(): Record
    {
        return new Record($this->ispConfig, 'cname');
    }

    public function mx(): Record
    {
        return new Record($this->ispConfig, 'mx');
    }

    public function ns(): Record
    {
        return new Record($this->ispConfig, 'ns');
    }

    public function txt(): Record
    {
        return new Record($this->ispConfig, 'txt');
    }

    public function srv(): Record
    {
        return new Record($this->ispConfig, 'srv');
    }

    public function ptr(): Record
    {
        return new Record($this->ispConfig, 'ptr');
    }

    public function alias(): Record
    {
        return new Record($this->ispConfig, 'alias');
    }

    public function hinfo(): Record
    {
        return new Record($this->ispConfig, 'hinfo');
    }

    public function rp(): Record
    {
        return new Record($this->ispConfig, 'rp');
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getAllByZone(int $zoneId): array
    {
        /** @var list<array<string, mixed>> */
        return $this->call('dns_rr_get_all_by_zone', $zoneId);
    }
}
