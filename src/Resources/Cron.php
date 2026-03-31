<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Resources;

final class Cron extends Resource
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function add(int $clientId, array $params): int
    {
        /** @var int */
        return $this->call('sites_cron_add', $clientId, $params);
    }

    /**
     * @return array<string, mixed>
     */
    public function get(int $id): array
    {
        /** @var array<string, mixed> */
        return $this->call('sites_cron_get', $id);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function update(int $clientId, int $id, array $params): int
    {
        /** @var int */
        return $this->call('sites_cron_update', $clientId, $id, $params);
    }

    public function delete(int $id): int
    {
        /** @var int */
        return $this->call('sites_cron_delete', $id);
    }
}
