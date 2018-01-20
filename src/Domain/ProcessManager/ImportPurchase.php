<?php

namespace ConferenceTools\Checkin\Domain\ProcessManager;

use Carnage\Cqrs\MessageHandler\AbstractMethodNameMessageHandler;
use Carnage\Cqrs\Persistence\EventStore\NotFoundException;
use Carnage\Cqrs\Persistence\Repository\RepositoryInterface;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateRegistered;
use ConferenceTools\Checkin\Domain\Event\Purchase\TicketAssigned;
use ConferenceTools\Checkin\Domain\Event\Purchase\TicketPurchasePaid;
use ConferenceTools\Checkin\Domain\Process\ImportPurchase as ImportPurchaseProcess;

class ImportPurchase extends AbstractMethodNameMessageHandler
{
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    protected function handleTicketAssigned(TicketAssigned $event)
    {
        $process = $this->loadProcess($event->getTicket()->getPurchaseId());
        $process->ticketAssigned($event);
        $this->repository->save($process);
    }

    protected function handleTicketPurchasePaid(TicketPurchasePaid $event)
    {
        $process = $this->loadProcess($event->getPurchaseId());
        $process->ticketPurchasePaid($event);
        $this->repository->save($process);
    }

    protected function handleDelegateRegistered(DelegateRegistered $event)
    {
        $process = $this->loadProcess($event->getTicket()->getPurchaseId());
        $process->delegateRegistered($event);
        $this->repository->save($process);
    }

    private function loadProcess($id): ImportPurchaseProcess
    {
        try {
            $process = $this->repository->load($id);
        } catch (NotFoundException $e) {
            $process = ImportPurchaseProcess::withId($id);
        }

        return $process;
    }
}