<?php

declare(strict_types=1);

namespace SmartDato\IspConfig;

use Illuminate\Contracts\Foundation\Application;
use SmartDato\IspConfig\Connectors\SoapConnector;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class IspConfigServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('isp-config')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(IspConfig::class, function (Application $app): IspConfig {
            /** @var array{host: string, port: int, username: string, password: string, verify_ssl: bool, timeout: int} $config */
            $config = $app['config']['isp-config'];

            return new IspConfig(
                connector: new SoapConnector(
                    host: $config['host'],
                    port: $config['port'],
                    verifySsl: $config['verify_ssl'],
                    timeout: $config['timeout'],
                ),
                username: $config['username'],
                password: $config['password'],
            );
        });
    }

    public function packageBooted(): void
    {
        $this->app->terminating(function (): void {
            if ($this->app->resolved(IspConfig::class)) {
                $this->app->make(IspConfig::class)->logout();
            }
        });
    }
}
