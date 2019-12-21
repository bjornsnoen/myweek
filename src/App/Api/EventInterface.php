<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Api;

use DateTimeInterface;

/**
 * Interface EventInterface
 * @package MyWeek\App
 */
interface EventInterface
{
    /**
     * Time when the event happened
     *
     * @return DateTimeInterface
     */
    public function getTime(): DateTimeInterface;

    /**
     * What happened
     *
     * @return string
     */
    public function getEventText(): string;

    /**
     * What kind of thing happened?
     *
     * @return string
     */
    public function getEventType(): string;
}
