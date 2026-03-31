<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Exceptions;

use Throwable;

final class ApiException extends IspConfigException
{
    public function __construct(
        public readonly string $method,
        string $message,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct("ISPConfig API error on '{$method}': {$message}", $code, $previous);
    }
}
