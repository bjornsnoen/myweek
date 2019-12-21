<?php

/**
 * @license GPLv3
 * @author Bjørn Snoen <bjorn.snoen@gmail.com>
 */

namespace MyWeek\App\Services;

use DI\DependencyException;
use DI\NotFoundException;
use DI\Container;
use Generator;
use Illuminate\Support\Collection;
use MyWeek\App\Api\EventInterface;
use MyWeek\App\Api\EventProviderProviderInterface;
use MyWeek\App\Api\RequiresInstallInterface;
use MyWeek\App\Exceptions\InstallFailedException;
use MyWeek\App\Parser\GlobalGitlog;
use Symfony\Component\Console\Style\SymfonyStyle;

class EventProviderProvider implements EventProviderProviderInterface
{
    /**
     * @var Container
     */

    private Container $container;
    /**
     * @var SymfonyStyle
     */
    private SymfonyStyle $io;

    public function __construct(Container $container, SymfonyStyle $io)
    {
        $this->container = $container;
        $this->io = $io;
    }

    /**
     * @inheritDoc
     *
     * @return Generator
     * @psalm-return Generator<\MyWeek\App\Api\EventProviderInterface>
     */
    public function getProviders(): Generator
    {
        try {
            yield $this->container->get(GlobalGitlog::class);
        } catch (DependencyException $e) {
        } catch (NotFoundException $e) {
        }
    }

    /**
     * @inheritDoc
     *
     * @param bool $sorted
     * @return Generator
     * @throws InstallFailedException
     */
    public function aggregateEvents(bool $sorted = true): Generator
    {
        $providers = $this->getProviders();
        foreach ($providers as $provider) {
            if ($provider instanceof RequiresInstallInterface && !$provider->isInstalled()) {
                $this->io->note(sprintf("The provider %s requires installation", $provider->getName()));
                if ($provider->getInstaller()->getInstallApproval()) {
                    $provider->getInstaller()->install();
                }
            }
        }
        // Generator was closed because of above loop, recreate
        $providers = $this->getProviders();
        if (!$sorted) {
            foreach ($providers as $provider) {
                foreach ($provider->getEvents() as $event) {
                    yield $event;
                }
            }
        } else {
            $events = new Collection([]);
            foreach ($providers as $provider) {
                $events = $events->concat($provider->getEvents());
            }
            $events = $events->sort(function (EventInterface $eventA, EventInterface $eventB) {
                if ($eventA->getTime()->getTimestamp() == $eventB->getTime()->getTimestamp()) {
                    return 0;
                }
                return ($eventA->getTime()->getTimestamp() < $eventB->getTime()->getTimestamp()) ? -1 : 1;
            });

            foreach ($events as $event) {
                yield $event;
            }
        }
    }
}
