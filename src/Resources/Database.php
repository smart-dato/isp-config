<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Resources;

use SmartDato\IspConfig\Resources\Database\User;

final class Database extends Resource
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function add(int $clientId, array $params): int
    {
        /** @var int */
        return $this->call('sites_database_add', $clientId, $params);
    }

    /**
     * @return array<string, mixed>
     */
    public function get(int $id): array
    {
        /** @var array<string, mixed> */
        return $this->call('sites_database_get', $id);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getAllByUser(int $clientId): array
    {
        /** @var list<array<string, mixed>> */
        return $this->call('sites_database_get_all_by_user', $clientId);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function update(int $clientId, int $id, array $params): int
    {
        /** @var int */
        return $this->call('sites_database_update', $clientId, $id, $params);
    }

    public function delete(int $id): int
    {
        /** @var int */
        return $this->call('sites_database_delete', $id);
    }

    public function getFreePort(int $serverId): int
    {
        /** @var int */
        return $this->call('sites_database_get_free_port', $serverId);
    }

    /**
     * @return array<string, mixed>
     */
    public function getVersion(int $serverId): array
    {
        /** @var array<string, mixed> */
        return $this->call('sites_databaseversion_get', $serverId);
    }

    public function user(): User
    {
        return new User($this->ispConfig);
    }
}
