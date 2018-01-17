<?php

namespace ConferenceTools\Checkin\Domain\CommandHandler;

use Carnage\Cqrs\Aggregate\Identity\GeneratorInterface;
use Carnage\Cqrs\MessageHandler\AbstractMethodNameMessageHandler;
use Carnage\Cqrs\Persistence\Repository\RepositoryInterface;
use ConferenceTools\Checkin\Domain\Command\Delegate\RegisterDelegate;
use ConferenceTools\Checkin\Domain\Model\Delegate\Delegate as DelegateModel;

class Delegate extends AbstractMethodNameMessageHandler
{
    private $idGenerator;

    private $repository;

    /**
     * Delegate constructor.
     * @param $idGenerator
     * @param $repository
     */
    public function __construct(RepositoryInterface $repository, GeneratorInterface $idGenerator)
    {
        $this->idGenerator = $idGenerator;
        $this->repository = $repository;
    }

    protected function handleRegisterDelegate(RegisterDelegate $command)
    {
        $delegate = DelegateModel::register(
            $this->idGenerator->generateIdentity(),
            $command->getDelegateInfo(),
            $command->getTicket(),
            $command->getPurchaserEmail()
        );

        $this->repository->save($delegate);
    }
}