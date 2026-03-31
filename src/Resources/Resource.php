<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Resources;

use SmartDato\IspConfig\IspConfig;

abstract class Resource
{
    public function __construct(
        protected readonly IspConfig $ispConfig,
    ) {}

    protected function call(string $method, mixed ...$params): mixed
    {
        return $this->ispConfig->call($method, ...$params);
    }
}
