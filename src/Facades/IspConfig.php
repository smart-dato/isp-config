<?php

declare(strict_types=1);

namespace SmartDato\IspConfig\Facades;

use Illuminate\Support\Facades\Facade;
use SmartDato\IspConfig\Connectors\FakeConnector;

/**
 * @see \SmartDato\IspConfig\IspConfig
 *
 * @method static \SmartDato\IspConfig\IspConfig make(array $config)
 * @method static string login()
 * @method static void logout()
 * @method static mixed call(string $method, mixed ...$params)
 * @method static \SmartDato\IspConfig\Resources\Client client()
 * @method static \SmartDato\IspConfig\Resources\Server server()
 * @method static \SmartDato\IspConfig\Resources\Sites sites()
 * @method static \SmartDato\IspConfig\Resources\Mail mail()
 * @method static \SmartDato\IspConfig\Resources\Dns dns()
 * @method static \SmartDato\IspConfig\Resources\Database database()
 * @method static \SmartDato\IspConfig\Resources\Ftp ftp()
 * @method static \SmartDato\IspConfig\Resources\Shell shell()
 * @method static \SmartDato\IspConfig\Resources\Cron cron()
 * @method static list<string> getFunctionList()
 */
final class IspConfig extends Facade
{
    public static function fake(): FakeConnector
    {
        $fake = new FakeConnector;
        $fake->stub('login', 'fake-session-id');

        self::getFacadeRoot()->setConnector($fake);

        return $fake;
    }

    protected static function getFacadeAccessor(): string
    {
        return \SmartDato\IspConfig\IspConfig::class;
    }
}
