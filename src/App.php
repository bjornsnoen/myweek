<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

declare(strict_types=1);

namespace MyWeek;

use DI\ContainerBuilder;
use Generator;
use MyWeek\App\Api\WriterInterface;
use MyWeek\Definitions\CoreDefinitions;
use MyWeek\Definitions\FileSystemDefinitions;
use MyWeek\Definitions\SymfonyDefinitions;
use Psr\Container\ContainerInterface;

final class App
{
    /**
     * @param string $className
     * @return WriterInterface
     */
    public static function createWriter(string $className): WriterInterface
    {
        $container = static::getContainer();
        /** @var mixed $object */
        $object = $container->get($className);
        return $object;
    }

    private static function getContainer(): ContainerInterface
    {
        $builder = new ContainerBuilder();

        foreach (static::getDefinitionProviders() as $provider) {
            $builder->addDefinitions($provider->getDefinitions());
        }

        return $builder->build();
    }

    /**
     * @return Generator
     */
    private static function getDefinitionProviders(): Generator
    {
        yield new CoreDefinitions();
        yield new SymfonyDefinitions();
        yield new FileSystemDefinitions();
    }
}
