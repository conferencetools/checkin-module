<?php

namespace ConferenceTools\Checkin\Cli\Command;

use Carnage\Cqorms\Persistence\ReadModel\DoctrineRepository;
use ConferenceTools\Checkin\Domain\ReadModel\Delegate;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GenerateQRFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();
        return GenerateQR::build(
            new DoctrineRepository(Delegate::class, $serviceLocator->get(EntityManager::class))
        );
    }
}
