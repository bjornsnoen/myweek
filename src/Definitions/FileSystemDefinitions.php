<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\Definitions;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use MyWeek\App\Api\ExecutionEnablerInterface;
use MyWeek\App\Services\ExecutionEnabler;

use function DI\autowire;

class FileSystemDefinitions implements DefinitionProviderInterface
{

    public function getDefinitions(): array
    {
        return [
            FilesystemInterface::class => autowire(Filesystem::class)->constructorParameter(
                'adapter',
                autowire(Local::class)->constructorParameter('root', '/')
            ),

            ExecutionEnablerInterface::class => autowire(ExecutionEnabler::class)->constructorParameter(
                'executionConfiguredFileSystem',
                autowire(Filesystem::class)->constructorParameter(
                    'adapter',
                    autowire(Local::class)->constructorParameter('root', '/')->constructorParameter(
                        'permissions',
                        [
                            'file' => [
                                'public' => 0744,
                                'private' => 0700,
                            ]
                        ]
                    )
                )
            ),

            'resourceLocation' => dirname(__FILE__) . '/../resources',
        ];
    }
}
