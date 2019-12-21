<?php

/**
 * @license GPLv3
 * @author Marcus Pettersen Irgens <marcus.irgens@visma.com>
 */

declare(strict_types=1);

namespace MyWeek\Definitions;

interface DefinitionProviderInterface
{
    public function getDefinitions(): array;
}
