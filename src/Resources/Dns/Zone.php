<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Resources\Dns;

use SmartDato\IspConfig\Resources\Resource;

final class Zone extends Resource
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function add(int $clientId, array $params): int
    {
        /** @var int */
        return $this->call('dns_zone_add', $clientId, $params);
    }

    /**
     * @return array<string, mixed>
     */
    public function get(int $id): array
    {
        /** @var array<string, mixed> */
        return $this->call('dns_zone_get', $id);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getByUser(int $clientId): array
    {
        /** @var list<array<string, mixed>> */
        return $this->call('dns_zone_get_by_user', $clientId);
    }

    public function getId(string $origin): int
    {
        /** @var int */
        return $this->call('dns_zone_get_id', $origin);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function update(int $clientId, int $id, array $params): int
    {
        /** @var int */
        return $this->call('dns_zone_update', $clientId, $id, $params);
    }

    public function delete(int $id): int
    {
        /** @var int */
        return $this->call('dns_zone_delete', $id);
    }

    public function setStatus(int $id, string $status): mixed
    {
        return $this->call('dns_zone_set_status', $id, $status);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function addTemplate(int $clientId, array $params): int
    {
        /** @var int */
        return $this->call('dns_templatezone_add', $clientId, $params);
    }
}
