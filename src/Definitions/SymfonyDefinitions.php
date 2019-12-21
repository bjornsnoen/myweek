<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\Definitions;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

use function DI\autowire;

class SymfonyDefinitions implements DefinitionProviderInterface
{

    public function getDefinitions(): array
    {
        return [
            InputInterface::class => autowire(ArgvInput::class),
            OutputInterface::class => autowire(ConsoleOutput::class)
                ->constructorParameter('decorated', true)
        ];
    }
}
