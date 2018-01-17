<?php

namespace ConferenceTools\Checkin\Domain\Projection;

use Carnage\Cqrs\MessageHandler\AbstractMethodNameMessageHandler;
use Carnage\Cqrs\Persistence\ReadModel\RepositoryInterface;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateRegistered;
use ConferenceTools\Checkin\Domain\ReadModel\Delegate as DelegateModel;

class Delegate extends AbstractMethodNameMessageHandler
{
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handleDelegateRegistered(DelegateRegistered $event)
    {
        $entity = new DelegateModel(
            $event->getDelegateId(),
            $event->getDelegateInfo(),
            $event->getTicket(),
            $event->getPurchaserEmail()
        );
        $this->repository->add($entity);
        $this->repository->commit();
    }
}