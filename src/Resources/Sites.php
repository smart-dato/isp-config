<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Resources;

use SmartDato\IspConfig\Resources\Sites\AliasDomain;
use SmartDato\IspConfig\Resources\Sites\Folder;
use SmartDato\IspConfig\Resources\Sites\FolderUser;
use SmartDato\IspConfig\Resources\Sites\Subdomain;
use SmartDato\IspConfig\Resources\Sites\VhostAliasDomain;
use SmartDato\IspConfig\Resources\Sites\VhostSubdomain;
use SmartDato\IspConfig\Resources\Sites\WebDomain;

final class Sites extends Resource
{
    public function webDomain(): WebDomain
    {
        return new WebDomain($this->ispConfig);
    }

    public function subdomain(): Subdomain
    {
        return new Subdomain($this->ispConfig);
    }

    public function aliasDomain(): AliasDomain
    {
        return new AliasDomain($this->ispConfig);
    }

    public function vhostSubdomain(): VhostSubdomain
    {
        return new VhostSubdomain($this->ispConfig);
    }

    public function vhostAliasDomain(): VhostAliasDomain
    {
        return new VhostAliasDomain($this->ispConfig);
    }

    public function folder(): Folder
    {
        return new Folder($this->ispConfig);
    }

    public function folderUser(): FolderUser
    {
        return new FolderUser($this->ispConfig);
    }
}
