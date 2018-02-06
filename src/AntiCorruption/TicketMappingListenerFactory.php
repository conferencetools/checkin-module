<?php

namespace ConferenceTools\Checkin\AntiCorruption;

use Carnage\Cqrs\Event\EventManagerInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TicketMappingListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator, $cName = null, $rName = null)
    {
        return $this($serviceLocator->getServiceLocator(), $rName);
    }

    public function __invoke(ContainerInterface $container, $name, $options = [])
    {
        return new TicketMappingListener($container->get(EventManagerInterface::class));
    }
}