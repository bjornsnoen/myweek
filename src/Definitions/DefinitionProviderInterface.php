<?php

/**
 * @license GPLv3
 */

declare(strict_types=1);

namespace MyWeek\Definitions;

interface DefinitionProviderInterface
{
    public function getDefinitions(): array;
}
