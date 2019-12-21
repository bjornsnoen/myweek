<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Api;

interface RequiresInstallInterface
{
    /**
     * @return bool
     * @author Bjørn Snoen <bjorn.snoen@gmail.com>
     */
    public function isInstalled(): bool;

    /**
     * @return InstallerInterface
     * @author Bjørn Snoen <bjorn.snoen@gmail.com>
     */
    public function getInstaller(): InstallerInterface;
}
