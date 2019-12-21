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
     * @var string
     */
    private string $eventType;

    /**
     * Event constructor.
     * @param DateTimeInterface $dateTime
     * @param string $eventText
     */
    public function __construct(DateTimeInterface $dateTime, string $eventText, string $eventType)
    {
        $this->dateTime = $dateTime;
        $this->eventText = $eventText;
        $this->eventType = $eventType;
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

    /**
     * @inheritDoc
     */
    public function getEventType(): string
    {
        return $this->eventType;
    }
}
