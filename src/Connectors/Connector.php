<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Connectors;

interface Connector
{
    /**
     * @param  array<int, mixed>  $params
     */
    public function call(string $method, array $params = []): mixed;
}
