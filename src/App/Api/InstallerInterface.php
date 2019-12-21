<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Api;

use MyWeek\App\Exceptions\InstallFailedException;

interface InstallerInterface
{
    /**
     * @return bool
     * @throws InstallFailedException
     * @author Bjørn Snoen <bjorn.snoen@gmail.com>
     */
    public function install(): bool;

    public function getInstallApproval(): bool;
}
