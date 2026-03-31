<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Resources;

final class Client extends Resource
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function add(int $resellerId, array $params): int
    {
        /** @var int */
        return $this->call('client_add', $resellerId, $params);
    }

    /**
     * @return array<string, mixed>
     */
    public function get(int $clientId): array
    {
        /** @var array<string, mixed> */
        return $this->call('client_get', $clientId);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getAll(): array
    {
        /** @var list<array<string, mixed>> */
        return $this->call('client_get_all');
    }

    /**
     * @return array<string, mixed>
     */
    public function getByUsername(string $username): array
    {
        /** @var array<string, mixed> */
        return $this->call('client_get_by_username', $username);
    }

    /**
     * @return array<string, mixed>
     */
    public function getByCustomerNo(string $customerNo): array
    {
        /** @var array<string, mixed> */
        return $this->call('client_get_by_customer_no', $customerNo);
    }

    public function getId(string $username): int
    {
        /** @var int */
        return $this->call('client_get_id', $username);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function update(int $clientId, int $resellerId, array $params): int
    {
        /** @var int */
        return $this->call('client_update', $clientId, $resellerId, $params);
    }

    public function delete(int $clientId): int
    {
        /** @var int */
        return $this->call('client_delete', $clientId);
    }

    public function deleteEverything(int $clientId): int
    {
        /** @var int */
        return $this->call('client_delete_everything', $clientId);
    }

    public function changePassword(int $clientId, string $password): int
    {
        /** @var int */
        return $this->call('client_change_password', $clientId, $password);
    }

    /**
     * @return array<string, mixed>
     */
    public function getEmailContact(int $clientId): array
    {
        /** @var array<string, mixed> */
        return $this->call('client_get_emailcontact', $clientId);
    }

    public function getGroupId(int $clientId): int
    {
        /** @var int */
        return $this->call('client_get_groupid', $clientId);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getSitesByUser(int $userId, int $groupId): array
    {
        /** @var list<array<string, mixed>> */
        return $this->call('client_get_sites_by_user', $userId, $groupId);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function templatesGetAll(): array
    {
        /** @var list<array<string, mixed>> */
        return $this->call('client_templates_get_all');
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function templateAdditionalAdd(int $clientId, array $params): int
    {
        /** @var int */
        return $this->call('client_template_additional_add', $clientId, $params);
    }

    /**
     * @return array<string, mixed>
     */
    public function templateAdditionalGet(int $id): array
    {
        /** @var array<string, mixed> */
        return $this->call('client_template_additional_get', $id);
    }

    public function templateAdditionalDelete(int $id): int
    {
        /** @var int */
        return $this->call('client_template_additional_delete', $id);
    }
}
