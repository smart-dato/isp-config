<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Resources\Mail;

use SmartDato\IspConfig\Resources\Resource;

final class UserFilter extends Resource
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function add(int $clientId, array $params): int
    {
        /** @var int */
        return $this->call('mail_user_filter_add', $clientId, $params);
    }

    /**
     * @return array<string, mixed>
     */
    public function get(int $id): array
    {
        /** @var array<string, mixed> */
        return $this->call('mail_user_filter_get', $id);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function update(int $clientId, int $id, array $params): int
    {
        /** @var int */
        return $this->call('mail_user_filter_update', $clientId, $id, $params);
    }

    public function delete(int $id): int
    {
        /** @var int */
        return $this->call('mail_user_filter_delete', $id);
    }
}
