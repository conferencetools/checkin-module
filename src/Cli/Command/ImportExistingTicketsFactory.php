<?php

namespace ConferenceTools\Checkin\Cli\Command;

use Carnage\Cqrs\Persistence\EventStore\EventStoreInterface;
use ConferenceTools\Checkin\AntiCorruption\TicketMappingListener;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImportExistingTicketsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();
        return $this($serviceLocator, ImportExistingTickets::class);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        return ImportExistingTickets::build(
            $config['domain_event_subscriptions'],
            $container->get('EventListenerManager')->get(TicketMappingListener::class),
            $container->get(EventStoreInterface::class)
        );
    }
}