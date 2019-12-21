<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Api;

use DateTimeInterface;

interface EventFactoryInterface
{
    /**
     * @param DateTimeInterface $dateTime
     * @param string $eventText
     * @param string $eventType
     * @return EventInterface
     * @author Bjørn Snoen <bjorn.snoen@gmail.com>
     */
    public function create(DateTimeInterface $dateTime, string $eventText, string $eventType): EventInterface;
}
