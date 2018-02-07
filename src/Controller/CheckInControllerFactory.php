<?php

namespace ConferenceTools\Checkin\Controller;

use Carnage\Cqorms\Persistence\ReadModel\DoctrineRepository;
use Carnage\Cqrs\Command\CommandBusInterface;
use ConferenceTools\Checkin\Domain\ReadModel\Delegate;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CheckInControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator, $cName = null, $rName = null)
    {
        return $this($serviceLocator->getServiceLocator(), $rName);
    }

    public function __invoke(ContainerInterface $container, $name, $options = [])
    {
        return new CheckInController(
            $container->get(CommandBusInterface::class),
            new DoctrineRepository(Delegate::class, $container->get(EntityManager::class))
        );
    }
}