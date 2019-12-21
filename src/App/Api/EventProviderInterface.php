<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Api;

use Generator;
use MyWeek\App\Exceptions\NotInstalledException;

interface EventProviderInterface
{
    /**
     * @return Generator
     *
     * @throws NotInstalledException
     *
     * @author Bjørn Snoen <bjorn.snoen@gmail.com>
     */
    public function getEvents(): Generator;

    /**
     * @return string The name of the event provider, shown to user
     * @author Bjørn Snoen <bjorn.snoen@gmail.com>
     */
    public function getName(): string;
}
