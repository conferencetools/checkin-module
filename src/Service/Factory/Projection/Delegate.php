<?php

namespace ConferenceTools\Checkin\Service\Factory\Projection;

use ConferenceTools\Checkin\Domain\Projection\Delegate as DelegateProjection;
use ConferenceTools\Checkin\Domain\ReadModel\Delegate as DelegateReadModel;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Carnage\Cqorms\Persistence\ReadModel\DoctrineRepository;

class Delegate implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator, $cName = null, $rName = null)
    {
        return $this($serviceLocator->getServiceLocator(), $rName);
    }

    public function __invoke(ContainerInterface $container, $name, $options = [])
    {
        return new DelegateProjection(
            new DoctrineRepository(DelegateReadModel::class, $container->get(EntityManager::class))
        );
    }
}