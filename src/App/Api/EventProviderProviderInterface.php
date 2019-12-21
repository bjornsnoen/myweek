<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Api;

use Generator;

interface EventProviderProviderInterface
{
    /**
     * @return Generator
     *
     * @author Bjørn Snoen <bjorn.snoen@gmail.com>
     */
    public function getProviders(): Generator;

    /**
     * @param bool $sorted
     *
     * @return Generator
     * @psalm-return Generator<\MyWeek\App\Api\EventInterface>
     * @author Bjørn Snoen <bjorn.snoen@gmail.com>
     */
    public function aggregateEvents(bool $sorted = true): Generator;
}
