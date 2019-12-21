<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

declare(strict_types=1);

namespace MyWeek\Definitions;

use MyWeek\App\Api\EventFactoryInterface;
use MyWeek\App\Api\EventProviderProviderInterface;
use MyWeek\App\Api\WriterInterface;
use MyWeek\App\Factories\EventFactory;
use MyWeek\App\Parser\GlobalGitlog;
use MyWeek\App\Services\EventProviderProvider;
use MyWeek\App\Writer\Stdout;

use function DI\autowire;
use function DI\get;

class CoreDefinitions implements DefinitionProviderInterface
{
    public function getDefinitions(): array
    {
        return [
            EventFactoryInterface::class => autowire(EventFactory::class),
            WriterInterface::class => autowire(Stdout::class),
            EventProviderProviderInterface::class => autowire(EventProviderProvider::class),
            GlobalGitlog::class => autowire()->constructorParameter(
                'resourceLocation',
                get('resourceLocation')
            )->constructorParameter(
                'homeDirectory',
                getenv('HOME')
            ),
        ];
    }
}
