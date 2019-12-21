<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Api;

use DateTimeInterface;

interface EventFactoryInterface
{
    public function create(DateTimeInterface $dateTime, string $eventText): EventInterface;
}
