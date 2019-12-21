<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Api;

use League\Flysystem\FileNotFoundException;

interface ExecutionEnablerInterface
{
    /**
     * @param string $filePath
     * @author Bjørn Snoen <bjorn.snoen@gmail.com>
     * @throws FileNotFoundException
     */
    public function enable(string $filePath): void;
}
