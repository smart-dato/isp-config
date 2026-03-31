<?php

declare(strict_types=1);

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('it uses strict types')
    ->expect('SmartDato\IspConfig')
    ->toUseStrictTypes();

arch('all concrete classes are final')
    ->expect('SmartDato\IspConfig')
    ->classes()
    ->toBeFinal()
    ->ignoring('SmartDato\IspConfig\Resources\Resource')
    ->ignoring('SmartDato\IspConfig\Exceptions\IspConfigException')
    ->ignoring('SmartDato\IspConfig\Facades\IspConfig');

arch('exceptions extend base exception')
    ->expect('SmartDato\IspConfig\Exceptions')
    ->toExtend('SmartDato\IspConfig\Exceptions\IspConfigException')
    ->ignoring('SmartDato\IspConfig\Exceptions\IspConfigException');

arch('resources extend base resource')
    ->expect('SmartDato\IspConfig\Resources')
    ->classes()
    ->toExtend('SmartDato\IspConfig\Resources\Resource');

arch('connectors implement connector interface')
    ->expect('SmartDato\IspConfig\Connectors\SoapConnector')
    ->toImplement('SmartDato\IspConfig\Connectors\Connector');

arch('connectors implement connector interface (fake)')
    ->expect('SmartDato\IspConfig\Connectors\FakeConnector')
    ->toImplement('SmartDato\IspConfig\Connectors\Connector');
