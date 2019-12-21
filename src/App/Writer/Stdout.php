<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Writer;

use MyWeek\App\Api\EventProviderProviderInterface;
use MyWeek\App\Api\WriterInterface;

class Stdout implements WriterInterface
{
    /**
     * @var EventProviderProviderInterface
     */
    private EventProviderProviderInterface $eventProviderProvider;

    public function __construct(EventProviderProviderInterface $eventProviderProvider)
    {
        $this->eventProviderProvider = $eventProviderProvider;
    }

    /**
     * @inheritDoc
     */
    public function write()
    {
        foreach ($this->eventProviderProvider->aggregateEvents() as $event) {
            echo sprintf("%s: %s\n", $event->getTime()->format('Y-m-d H:i'), $event->getEventText());
        }
    }
}
