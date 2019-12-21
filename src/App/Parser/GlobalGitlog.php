<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Parser;

use DateTimeImmutable;
use Generator;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use MyWeek\App\Api\EventFactoryInterface;
use MyWeek\App\Api\EventProviderInterface;
use MyWeek\App\Api\ExecutionEnablerInterface;
use MyWeek\App\Api\InstallerInterface;
use MyWeek\App\Api\RequiresInstallInterface;
use MyWeek\App\Exceptions\InstallFailedException;
use MyWeek\App\Exceptions\NotInstalledException;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use Webmozart\Assert\Assert;

class GlobalGitlog implements EventProviderInterface, InstallerInterface, RequiresInstallInterface
{
    private const GITLOG_DATE_FORMAT = "D, d M Y H:i:s O";

    /**
     * @var EventFactoryInterface
     */
    private EventFactoryInterface $eventFactory;

    /**
     * @var SymfonyStyle
     */
    private SymfonyStyle $io;

    /**
     * @var FilesystemInterface
     */
    private FilesystemInterface $filesystem;

    /**
     * @var string
     */
    private string $resourceLocation;

    /**
     * @var ExecutionEnablerInterface
     */
    private ExecutionEnablerInterface $executionEnabler;

    /**
     * @var string
     */
    private string $homeDirectory;

    public function __construct(
        EventFactoryInterface $eventFactory,
        SymfonyStyle $io,
        FilesystemInterface $filesystem,
        ExecutionEnablerInterface $executionEnabler,
        string $resourceLocation,
        string $homeDirectory
    ) {
        $this->eventFactory = $eventFactory;
        $this->io = $io;
        $this->filesystem = $filesystem;
        $this->resourceLocation = $resourceLocation;
        $this->executionEnabler = $executionEnabler;
        $this->homeDirectory = $homeDirectory;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return "Global gitlog";
    }

    /**
     * @inheritDoc
     *
     * @throws NotInstalledException
     *
     * @return Generator
     * @psalm-return Generator<\MyWeek\App\Api\EventInterface>
     */
    public function getEvents(): Generator
    {
        $globalGitlog = $this->getGlobalGitlogLocation();

        if (!is_file($globalGitlog)) {
            throw new NotInstalledException("Global gitlog not found! Expected in " . $globalGitlog);
        }

        $contents = array_filter(explode("\n", file_get_contents($globalGitlog)));

        $now = new DateTimeImmutable();
        $lastMonday = $now->setISODate(
            (int)$now->format('Y'),
            (int)$now->format('W'),
            1
        )->setTime(0, 0, 0);

        $lengthOfGitlogStamp = strlen($lastMonday->format(self::GITLOG_DATE_FORMAT));

        foreach ($contents as $commit) {
            $timestamp = substr($commit, 0, $lengthOfGitlogStamp);
            $text = trim(substr($commit, $lengthOfGitlogStamp + 1));
            $time = DateTimeImmutable::createFromFormat(self::GITLOG_DATE_FORMAT, $timestamp);
            if ($time >= $lastMonday) {
                yield $this->eventFactory->create($time, $text, 'Git commit');
            }
        }
    }

    protected function getGlobalGitlogLocation(): string
    {
        return $this->homeDirectory . '/global-gitlog.txt';
    }

    protected function getGitTemplateDir(): string
    {
        return $this->homeDirectory . '/.git-templates';
    }

    protected function getGitHookLocation(): string
    {
        return $this->getGitTemplateDir() . '/hooks/post-commit';
    }

    public function getInstallApproval(): bool
    {
        $this->io->writeln("This will create a few files in order to maintain a global gitlog");
        $this->io->table(
            ['File', 'Location'],
            [
                ['global-gitlog.txt', $this->homeDirectory],
                ['post-commit', $this->homeDirectory . '/.git-templates/hooks']
            ]
        );
        $this->io->writeln([
            "We will also enable git templates in the global git config",
            "After that, any git project you create will have a hook that writes to the first file when you commit",
            "Any old git projects will need further action to get the hook. Simply cd to one and type `git init`."
        ]);
        return $this->io->confirm("Is this ok?");
    }

    /**
     * @inheritDoc
     */
    public function isInstalled(): bool
    {
        return $this->filesystem->has($this->getGlobalGitlogLocation());
    }

    /**
     * @inheritDoc
     */
    public function getInstaller(): InstallerInterface
    {
        return $this;
    }

    /**
     * @return bool
     * @throws InstallFailedException
     * @author Bjørn Snoen <bjorn.snoen@gmail.com>
     */
    public function install(): bool
    {
        try {
            $this->filesystem->write($this->getGlobalGitlogLocation(), '');
        } catch (FileExistsException $e) {
            // Guess this one exists, no big deal
        }

        $hookLocation = $this->resourceLocation . '/GlobalGitlog/post-commit';
        $targetLocation = $this->getGitHookLocation();

        try {
            $this->filesystem->copy($hookLocation, $targetLocation);
        } catch (FileExistsException $e) {
            $this->recoverFromExistingCommitHook($targetLocation, $hookLocation);
        } catch (FileNotFoundException $e) {
            // This one is bad, the app is corrupted
            throw new InstallFailedException("We couldn't find the git hook template, please re-install app");
        }

        try {
            $this->executionEnabler->enable($this->getGitHookLocation());
        } catch (FileNotFoundException $e) {
            throw new InstallFailedException("The git hook disappeared before execution could be enabled");
        }

        $process = Process::fromShellCommandline('git config --global init.templatedir "${:TEMPLATEDIR}"');
        $process->run(null, ['TEMPLATEDIR' => $this->getGitTemplateDir()]);

        return true;
    }

    /**
     * @param string $targetLocation
     * @param string $hookLocation
     * @throws InstallFailedException
     * @author Bjørn Snoen <bjorn.snoen@gmail.com>
     */
    protected function recoverFromExistingCommitHook(string $targetLocation, string $hookLocation): void
    {
        try {
            $existingFileContents = $this->filesystem->read($targetLocation);
            $ourFileContents = $this->filesystem->read($hookLocation);
        } catch (FileNotFoundException $e) {
            throw new InstallFailedException(
                "Global git hook exists, but we can't read it to verify the contents"
            );
        }

        if ($existingFileContents !== $ourFileContents) {
            Assert::string($ourFileContents);
            $overwrite = $this->io->ask(
                "You already have a global git post-commit hook. Can we overwrite it?"
            );
            if ($overwrite) {
                $this->filesystem->put($ourFileContents, $targetLocation);
            } else {
                throw new InstallFailedException();
            }
        }
    }
}
