<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Resources\Sites;

use SmartDato\IspConfig\Resources\Resource;

final class WebDomain extends Resource
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function add(int $clientId, array $params, bool $readonly = false): int
    {
        /** @var int */
        return $this->call('sites_web_domain_add', $clientId, $params, $readonly);
    }

    /**
     * @return array<string, mixed>
     */
    public function get(int $id): array
    {
        /** @var array<string, mixed> */
        return $this->call('sites_web_domain_get', $id);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function update(int $clientId, int $id, array $params): int
    {
        /** @var int */
        return $this->call('sites_web_domain_update', $clientId, $id, $params);
    }

    public function delete(int $id): int
    {
        /** @var int */
        return $this->call('sites_web_domain_delete', $id);
    }

    public function setStatus(int $id, string $status): mixed
    {
        return $this->call('sites_web_domain_set_status', $id, $status);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function backup(int $id, array $params): mixed
    {
        return $this->call('sites_web_domain_backup', $id, $params);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function backupList(int $id): array
    {
        /** @var list<array<string, mixed>> */
        return $this->call('sites_web_domain_backup_list', $id);
    }

    public function instantBackup(int $id): mixed
    {
        return $this->call('sites_web_domain_instant_backup', $id);
    }

    public function recreateVhost(int $id): mixed
    {
        return $this->call('sites_web_domain_recreate_vhost', $id);
    }
}
