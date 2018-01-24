<?php

namespace ConferenceTools\Checkin\Domain\Projection;

use Carnage\Cqrs\MessageHandler\AbstractMethodNameMessageHandler;
use Carnage\Cqrs\Persistence\ReadModel\RepositoryInterface;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateInformationUpdated;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateRegistered;
use ConferenceTools\Checkin\Domain\ReadModel\Delegate as DelegateModel;
use Doctrine\Common\Collections\Criteria;

class Delegate extends AbstractMethodNameMessageHandler
{
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    protected function handleDelegateRegistered(DelegateRegistered $event)
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

    protected function handleDelegateInformationUpdated(DelegateInformationUpdated $event)
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('delegateId', $event->getId()));
        /** @var DelegateModel $model */
        $model = $this->repository->matching($criteria)->current();
        $model->updateDelegateInfo($event->getDelegateInfo());
        $this->repository->commit();
    }
}