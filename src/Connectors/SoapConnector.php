<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Connectors;

use SmartDato\IspConfig\Exceptions\ConnectionException;
use SoapClient;
use SoapFault;

final class SoapConnector implements Connector
{
    private ?SoapClient $client = null;

    public function __construct(
        private readonly string $host,
        private readonly int $port,
        private readonly bool $verifySsl,
        private readonly int $timeout,
    ) {}

    public function call(string $method, array $params = []): mixed
    {
        try {
            return $this->getClient()->__soapCall($method, $params);
        } catch (SoapFault $e) {
            throw new ConnectionException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    private function getClient(): SoapClient
    {
        if ($this->client instanceof SoapClient) {
            return $this->client;
        }

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => $this->verifySsl,
                'verify_peer_name' => $this->verifySsl,
            ],
        ]);

        try {
            $this->client = new SoapClient(null, [
                'location' => "https://{$this->host}:{$this->port}/remote/index.php",
                'uri' => "https://{$this->host}:{$this->port}/remote/",
                'trace' => true,
                'exceptions' => true,
                'stream_context' => $context,
                'connection_timeout' => $this->timeout,
            ]);
        } catch (SoapFault $e) {
            throw new ConnectionException("Failed to connect to ISPConfig at {$this->host}:{$this->port}: {$e->getMessage()}", (int) $e->getCode(), $e);
        }

        return $this->client;
    }
}
