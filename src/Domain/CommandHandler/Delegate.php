<?php

namespace ConferenceTools\Checkin\Domain\CommandHandler;

use Carnage\Cqrs\Aggregate\Identity\GeneratorInterface;
use Carnage\Cqrs\MessageHandler\AbstractMethodNameMessageHandler;
use Carnage\Cqrs\Persistence\Repository\RepositoryInterface;
use ConferenceTools\Checkin\Domain\Command\Delegate\CheckInDelegate;
use ConferenceTools\Checkin\Domain\Command\Delegate\RegisterDelegate;
use ConferenceTools\Checkin\Domain\Command\Delegate\UpdateDelegateInformation;
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

    protected function handleRegisterDelegate(RegisterDelegate $command): void
    {
        $delegate = DelegateModel::register(
            $this->idGenerator->generateIdentity(),
            $command->getDelegateInfo(),
            $command->getTicket(),
            $command->getPurchaserEmail()
        );

        $this->repository->save($delegate);
    }

    protected function handleUpdateDelegateInformation(UpdateDelegateInformation $command): void
    {
        $delegate = $this->loadDelegate($command->getDelegateId());
        $delegate->updateDelegateInformation($command->getDelegateInfo());
        $this->repository->save($delegate);
    }

    protected function handleCheckInDelegate(CheckInDelegate $command): void
    {
        $delegate = $this->loadDelegate($command->getId());
        $delegate->checkIn();
        $this->repository->save($delegate);
    }

    private function loadDelegate(string $delegateId): DelegateModel
    {
        return $this->repository->load($delegateId);
    }
}