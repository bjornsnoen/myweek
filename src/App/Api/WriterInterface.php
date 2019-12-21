<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Api;

interface WriterInterface
{
    public function __construct(EventProviderProviderInterface $eventProviderProvider);

    /**
     * @return void
     * @author Bjørn Snoen <bjorn.snoen@gmail.com>
     */
    public function write();
}
