<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Resources\Dns;

use SmartDato\IspConfig\IspConfig;
use SmartDato\IspConfig\Resources\Resource;

final class Record extends Resource
{
    public function __construct(
        IspConfig $ispConfig,
        private readonly string $type,
    ) {
        parent::__construct($ispConfig);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function add(int $clientId, array $params): int
    {
        /** @var int */
        return $this->call("dns_{$this->type}_add", $clientId, $params);
    }

    /**
     * @return array<string, mixed>
     */
    public function get(int $id): array
    {
        /** @var array<string, mixed> */
        return $this->call("dns_{$this->type}_get", $id);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function update(int $clientId, int $id, array $params): int
    {
        /** @var int */
        return $this->call("dns_{$this->type}_update", $clientId, $id, $params);
    }

    public function delete(int $id): int
    {
        /** @var int */
        return $this->call("dns_{$this->type}_delete", $id);
    }
}
