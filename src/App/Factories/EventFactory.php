<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Factories;

use DateTimeInterface;
use MyWeek\App\Api\EventFactoryInterface;
use MyWeek\App\Api\EventInterface;
use MyWeek\App\Model\Event;

/**
 * Class EventFactory
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */
class EventFactory implements EventFactoryInterface
{
    public function create(DateTimeInterface $dateTime, string $eventText, string $eventType): EventInterface
    {
        return new Event($dateTime, $eventText, $eventType);
    }
}
