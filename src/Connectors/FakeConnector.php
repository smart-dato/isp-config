<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Connectors;

use Closure;
use PHPUnit\Framework\Assert;

final class FakeConnector implements Connector
{
    /** @var array<string, list<mixed>> */
    private array $stubbedResponses = [];

    /** @var list<array{method: string, params: array<int, mixed>}> */
    private array $recorded = [];

    public function stub(string $method, mixed $response): self
    {
        $this->stubbedResponses[$method][] = $response;

        return $this;
    }

    public function call(string $method, array $params = []): mixed
    {
        $this->recorded[] = ['method' => $method, 'params' => $params];

        if (! isset($this->stubbedResponses[$method]) || $this->stubbedResponses[$method] === []) {
            return true;
        }

        if (count($this->stubbedResponses[$method]) === 1) {
            return $this->stubbedResponses[$method][0];
        }

        return array_shift($this->stubbedResponses[$method]);
    }

    public function assertCalled(string $method, ?Closure $callback = null): void
    {
        $calls = $this->recorded($method);

        Assert::assertNotEmpty($calls, "Expected [{$method}] to be called, but it was not.");

        if ($callback instanceof Closure) {
            foreach ($calls as $call) {
                if ($callback($call['params'])) {
                    return;
                }
            }

            Assert::fail("Expected [{$method}] to be called with matching params, but no call matched.");
        }
    }

    public function assertNotCalled(string $method): void
    {
        Assert::assertEmpty(
            $this->recorded($method),
            "Unexpected call to [{$method}].",
        );
    }

    public function assertCallCount(string $method, int $expected): void
    {
        $actual = count($this->recorded($method));

        Assert::assertSame($expected, $actual, "Expected [{$method}] to be called {$expected} times, but was called {$actual} times.");
    }

    /**
     * @return list<array{method: string, params: array<int, mixed>}>
     */
    public function recorded(?string $method = null): array
    {
        if ($method === null) {
            return $this->recorded;
        }

        return array_values(
            array_filter($this->recorded, fn (array $call): bool => $call['method'] === $method),
        );
    }
}
