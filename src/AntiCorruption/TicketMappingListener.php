<?php

namespace ConferenceTools\Checkin\AntiCorruption;

use Carnage\Cqrs\Event\DomainMessage;
use Carnage\Cqrs\MessageBus\MessageBusInterface;
use Carnage\Cqrs\MessageHandler\AbstractMethodNameMessageHandler;
use ConferenceTools\Checkin\Domain\Event\Purchase\TicketAssigned;
use ConferenceTools\Checkin\Domain\Event\Purchase\TicketCreated;
use ConferenceTools\Checkin\Domain\Event\Purchase\TicketPurchasePaid;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;

class TicketMappingListener extends AbstractMethodNameMessageHandler
{
    private $eventBus;

    /**
     * TicketMappingListener constructor.
     * @param $eventBus
     */
    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    protected function handleTicketAssigned($event): void
    {
        $delegate = new DelegateInfo(
            $event->getDelegate()->getFirstname(),
            $event->getDelegate()->getLastName(),
            $event->getDelegate()->getEmail()
        );

        $ticket = new Ticket($event->getPurchaseId(), $event->getTicketId());

        $mappedEvent = new TicketAssigned($delegate, $ticket);
        $this->eventBus->dispatch($mappedEvent);
    }

    protected function handleTicketPurchasePaid($event): void
    {
        $mappedEvent = new TicketPurchasePaid($event->getId(), $event->getPurchaserEmail());
        $this->eventBus->dispatch($mappedEvent);
    }

    protected function handleTicketReserved($event): void
    {
        if ($event->getTicketType()->isSupplementary()) {
            return;
        }
        
        $mappedEvent = new TicketCreated(new Ticket($event->getPurchaseId(), $event->getId()));
        $this->eventBus->dispatch($mappedEvent);
    }
}