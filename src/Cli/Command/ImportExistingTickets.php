<?php

namespace ConferenceTools\Checkin\Cli\Command;

use Carnage\Cqrs\Event\Projection\PostRebuildInterface;
use Carnage\Cqrs\Event\Projection\PreRebuildInterface;
use Carnage\Cqrs\Event\Projection\ResettableInterface;
use Carnage\Cqrs\MessageHandler\MessageHandlerInterface;
use Carnage\Cqrs\Persistence\EventStore\LoadEventsInterface;
use ConferenceTools\Checkin\AntiCorruption\TicketMappingListener;
use ConferenceTools\Checkin\Domain\ProcessManager\ImportPurchase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImportExistingTickets extends Command
{
    /**
     * @var array
     */
    private $eventTypes;

    /**
     * @var TicketMappingListener
     */
    private $ticketMappingListener;

    /**
     * @var LoadEventsInterface
     */
    private $eventStore;

    public static function build(
        array $subscriptions,
        TicketMappingListener $ticketMappingListener,
        LoadEventsInterface $eventStore
    ) {
        $instance = new static();

        foreach ($subscriptions as $event => $listeners) {
            foreach ((array) $listeners as $listener) {
                if ($listener === TicketMappingListener::class) {
                    $instance->eventTypes[] = $event;
                }
            }
        }

        $instance->eventStore = $eventStore;
        $instance->ticketMappingListener = $ticketMappingListener;

        return $instance;
    }

    protected function configure()
    {
        $this->setName('checkin:import-existing-tickets')
            ->setDescription('Imports existing tickets from the event store.')
            ->setDefinition([
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $events = $this->eventStore->loadEventsByTypes(...$this->eventTypes);

        foreach ($events as $event) {
            $this->ticketMappingListener->handleDomainMessage($event);
        }
    }
}
