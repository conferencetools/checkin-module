<?php

namespace ConferenceTools\Checkin\Service\Factory\CommandHandler;

use Carnage\Cqrs\Aggregate\Identity\YouTubeStyleIdentityGenerator;
use ConferenceTools\Checkin\Domain\CommandHandler\Delegate as DelegateCommandHandler;
use ConferenceTools\Checkin\Domain\Model\Delegate\Delegate as DelegateModel;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Carnage\Cqrs\Persistence\Repository\PluginManager;

class Delegate implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator, $cName = null, $rName = null)
    {
        return $this($serviceLocator->getServiceLocator(), $rName);
    }

    public function __invoke(ContainerInterface $container, $name, $options = [])
    {
        $repositoryManager = $container->get(PluginManager::class);

        return new DelegateCommandHandler(
            $repositoryManager->get(DelegateModel::class),
            new YouTubeStyleIdentityGenerator()
        );

    }
}