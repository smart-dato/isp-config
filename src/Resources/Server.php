<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Resources;

final class Server extends Resource
{
    /**
     * @return array<string, mixed>
     */
    public function get(int $serverId): array
    {
        /** @var array<string, mixed> */
        return $this->call('server_get', $serverId);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getAll(): array
    {
        /** @var list<array<string, mixed>> */
        return $this->call('server_get_all');
    }

    /**
     * @return array<string, mixed>
     */
    public function getFunctions(int $serverId): array
    {
        /** @var array<string, mixed> */
        return $this->call('server_get_functions', $serverId);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getPhpVersions(int $serverId): array
    {
        /** @var list<array<string, mixed>> */
        return $this->call('server_get_php_versions', $serverId);
    }

    public function getServerIdByIp(string $ipAddress): int
    {
        /** @var int */
        return $this->call('server_get_serverid_by_ip', $ipAddress);
    }

    public function getServerIdByName(string $serverName): int
    {
        /** @var int */
        return $this->call('server_get_serverid_by_name', $serverName);
    }

    /**
     * @return array<string, mixed>
     */
    public function getSysDatalogStatus(): array
    {
        /** @var array<string, mixed> */
        return $this->call('server_get_sys_datalog_status');
    }

    public function restartPhp(int $serverId, string $phpVersion): mixed
    {
        return $this->call('server_restart_php', $serverId, $phpVersion);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function ipAdd(int $clientId, array $params): int
    {
        /** @var int */
        return $this->call('server_ip_add', $clientId, $params);
    }

    /**
     * @return array<string, mixed>
     */
    public function ipGet(int $id): array
    {
        /** @var array<string, mixed> */
        return $this->call('server_ip_get', $id);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function ipUpdate(int $clientId, int $id, array $params): int
    {
        /** @var int */
        return $this->call('server_ip_update', $clientId, $id, $params);
    }

    public function ipDelete(int $id): int
    {
        /** @var int */
        return $this->call('server_ip_delete', $id);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function f2bBlacklistAdd(array $params): int
    {
        /** @var int */
        return $this->call('server_f2b_blacklist_add', $params);
    }

    public function f2bBlacklistDelete(int $id): int
    {
        /** @var int */
        return $this->call('server_f2b_blacklist_delete', $id);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function f2bBlacklistGetAll(): array
    {
        /** @var list<array<string, mixed>> */
        return $this->call('server_f2b_blacklist_get_all');
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function f2bWhitelistAdd(array $params): int
    {
        /** @var int */
        return $this->call('server_f2b_whitelist_add', $params);
    }

    public function f2bWhitelistDelete(int $id): int
    {
        /** @var int */
        return $this->call('server_f2b_whitelist_delete', $id);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function f2bWhitelistGetAll(): array
    {
        /** @var list<array<string, mixed>> */
        return $this->call('server_f2b_whitelist_get_all');
    }
}
