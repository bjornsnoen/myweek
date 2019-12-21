<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Model;

use DateTimeInterface;
use MyWeek\App\Api\EventInterface;

class Event implements EventInterface
{
    /**
     * @var DateTimeInterface
     */
    private DateTimeInterface $dateTime;
    /**
     * @var string
     */
    private string $eventText;

    /**
     * Event constructor.
     * @param DateTimeInterface $dateTime
     * @param string $eventText
     */
    public function __construct(DateTimeInterface $dateTime, string $eventText)
    {
        $this->dateTime = $dateTime;
        $this->eventText = $eventText;
    }

    /**
     * @inheritDoc
     */
    public function getTime(): DateTimeInterface
    {
        return $this->dateTime;
    }

    /**
     * @inheritDoc
     */
    public function getEventText(): string
    {
        return $this->eventText;
    }
}
