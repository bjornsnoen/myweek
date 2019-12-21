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
     * @author Bjørn Snoen <bjorn.snoen@gmail.com>
     */
    public function getTime(): DateTimeInterface;

    /**
     * What happened
     *
     * @return string
     * @author Bjørn Snoen <bjorn.snoen@gmail.com>
     */
    public function getEventText(): string;
}
