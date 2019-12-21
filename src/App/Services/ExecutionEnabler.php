<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Services;

use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use MyWeek\App\Api\ExecutionEnablerInterface;

class ExecutionEnabler implements ExecutionEnablerInterface
{
    /**
     * @var FilesystemInterface
     */
    private FilesystemInterface $executionConfiguredFileSystem;

    public function __construct(FilesystemInterface $executionConfiguredFileSystem)
    {
        $this->executionConfiguredFileSystem = $executionConfiguredFileSystem;
    }

    /**
     * @inheritDoc
     */
    public function enable(string $filePath): void
    {
        $contents = $this->executionConfiguredFileSystem->readAndDelete($filePath);
        if ($contents == false) {
            throw new FileNotFoundException("Could not read contents of " . $filePath);
        }
        $this->executionConfiguredFileSystem->put($filePath, $contents, ['visibility' => 'public']);
    }
}
