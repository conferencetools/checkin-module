<?php

namespace ConferenceTools\Checkin\Service\Factory\ProcessManager;

use ConferenceTools\Checkin\Domain\Process\ImportPurchase as ImportPurchaseProcess;
use ConferenceTools\Checkin\Domain\ProcessManager\ImportPurchase as ImportPurchaseProcessManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Carnage\Cqrs\Persistence\Repository\PluginManager;

class ImportPurchase implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator, $cName = null, $rName = null)
    {
        return $this($serviceLocator->getServiceLocator(), $rName);
    }

    public function __invoke(ContainerInterface $container, $name, $options = [])
    {
        $repositoryManager = $container->get(PluginManager::class);

        return new ImportPurchaseProcessManager(
            $repositoryManager->get(ImportPurchaseProcess::class)
        );
    }
}