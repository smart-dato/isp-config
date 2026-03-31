<?php

declare(strict_types=1);

namespace SmartDato\IspConfig;

use SmartDato\IspConfig\Connectors\Connector;
use SmartDato\IspConfig\Connectors\SoapConnector;
use SmartDato\IspConfig\Exceptions\ApiException;
use SmartDato\IspConfig\Exceptions\AuthenticationException;
use SmartDato\IspConfig\Exceptions\IspConfigException;
use SmartDato\IspConfig\Resources\Client;
use SmartDato\IspConfig\Resources\Cron;
use SmartDato\IspConfig\Resources\Database;
use SmartDato\IspConfig\Resources\Dns;
use SmartDato\IspConfig\Resources\Ftp;
use SmartDato\IspConfig\Resources\Mail;
use SmartDato\IspConfig\Resources\Server;
use SmartDato\IspConfig\Resources\Shell;
use SmartDato\IspConfig\Resources\Sites;

final class IspConfig
{
    private ?string $sessionId = null;

    private Connector $connector;

    public function __construct(
        Connector $connector,
        private readonly string $username,
        private readonly string $password,
    ) {
        $this->connector = $connector;
    }

    /**
     * @param  array{host: string, port?: int, username: string, password: string, verify_ssl?: bool, timeout?: int}  $config
     */
    public static function make(array $config): self
    {
        return new self(
            connector: new SoapConnector(
                host: $config['host'],
                port: $config['port'] ?? 8080,
                verifySsl: $config['verify_ssl'] ?? true,
                timeout: $config['timeout'] ?? 30,
            ),
            username: $config['username'],
            password: $config['password'],
        );
    }

    public function login(): string
    {
        if ($this->sessionId !== null) {
            return $this->sessionId;
        }

        try {
            $result = $this->connector->call('login', [$this->username, $this->password]);
        } catch (IspConfigException $e) {
            throw new AuthenticationException("Failed to authenticate with ISPConfig: {$e->getMessage()}", $e->getCode(), $e);
        }

        if (! is_string($result) || $result === '') {
            throw new AuthenticationException('ISPConfig login returned an invalid session ID.');
        }

        $this->sessionId = $result;

        return $this->sessionId;
    }

    public function logout(): void
    {
        if ($this->sessionId === null) {
            return;
        }

        try {
            $this->connector->call('logout', [$this->sessionId]);
        } catch (IspConfigException) {
            // Silently ignore logout failures
        } finally {
            $this->sessionId = null;
        }
    }

    public function call(string $method, mixed ...$params): mixed
    {
        $sessionId = $this->login();

        try {
            $result = $this->connector->call($method, [$sessionId, ...$params]);
        } catch (IspConfigException $e) {
            if ($this->isSessionExpired($e)) {
                $this->sessionId = null;
                $sessionId = $this->login();

                $result = $this->connector->call($method, [$sessionId, ...$params]);
            } else {
                throw $e;
            }
        }

        if ($result === false) {
            throw new ApiException($method, 'The API returned false.');
        }

        return $result;
    }

    public function getConnector(): Connector
    {
        return $this->connector;
    }

    public function setConnector(Connector $connector): void
    {
        $this->connector = $connector;
    }

    public function client(): Client
    {
        return new Client($this);
    }

    public function server(): Server
    {
        return new Server($this);
    }

    public function sites(): Sites
    {
        return new Sites($this);
    }

    public function mail(): Mail
    {
        return new Mail($this);
    }

    public function dns(): Dns
    {
        return new Dns($this);
    }

    public function database(): Database
    {
        return new Database($this);
    }

    public function ftp(): Ftp
    {
        return new Ftp($this);
    }

    public function shell(): Shell
    {
        return new Shell($this);
    }

    public function cron(): Cron
    {
        return new Cron($this);
    }

    /**
     * @return list<string>
     */
    public function getFunctionList(): array
    {
        /** @var list<string> */
        return $this->call('get_function_list');
    }

    private function isSessionExpired(IspConfigException $e): bool
    {
        $message = mb_strtolower($e->getMessage());

        return str_contains($message, 'session')
            || str_contains($message, 'not authenticated')
            || str_contains($message, 'login');
    }
}
