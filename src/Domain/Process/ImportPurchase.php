<?php

namespace ConferenceTools\Checkin\Domain\Process;

use Carnage\Cqrs\Aggregate\AbstractAggregate;
use Carnage\Cqrs\Process\NewProcessInterface;
use ConferenceTools\Checkin\Domain\Command\Delegate\RegisterDelegate;
use ConferenceTools\Checkin\Domain\Command\Delegate\UpdateDelegateInformation;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateRegistered;
use ConferenceTools\Checkin\Domain\Event\Purchase\TicketAssigned;
use ConferenceTools\Checkin\Domain\Event\Purchase\TicketPurchasePaid;

class ImportPurchase extends AbstractAggregate implements NewProcessInterface
{
    /**
     * @var string
     */
    private $purchaseId;

    /**
     * @var bool
     */
    private $paid = false;

    /**
     * @var TicketAssigned[]
     */
    private $tickets = [];

    /**
     * @var string
     */
    private $purchaserEmail;

    /**
     * @var array
     */
    private $delegates;

    public static function withId(string $id)
    {
        $instance = new self;
        $instance->purchaseId = $id;
        return $instance;
    }

    public function getId()
    {
        return $this->purchaseId;
    }

    /*
     * @TODO handle events arriving out of order.
     */
    public function ticketAssigned(TicketAssigned $event)
    {
        if (!$this->paid) {
            $this->apply($event);
        } else {
            $ticket = $event->getTicket();
            $this->apply(
                new UpdateDelegateInformation(
                    $this->delegates[$ticket->getPurchaseId()][$ticket->getTicketId()],
                    $event->getDelegateInfo()
                )
            );
        }
    }

    protected function applyUpdateDelegateInformation(UpdateDelegateInformation $command)
    {

    }

    protected function applyTicketAssigned(TicketAssigned $event)
    {
        $this->purchaseId = $event->getTicket()->getPurchaseId();
        $this->tickets[] = $event;
    }

    public function ticketPurchasePaid(TicketPurchasePaid $event)
    {
        $this->apply($event);

        foreach ($this->tickets as $ticket) {
            $this->apply(new RegisterDelegate($ticket->getDelegateInfo(), $ticket->getTicket(), $this->purchaserEmail));
        }
    }

    protected function applyTicketPurchasePaid(TicketPurchasePaid $event)
    {
        $this->purchaseId = $event->getPurchaseId();
        $this->paid = true;
        $this->purchaserEmail = $event->getPurchaserEmail();
    }

    protected function applyRegisterDelegate(RegisterDelegate $command)
    {
        array_shift($this->tickets);
    }

    public function delegateRegistered(DelegateRegistered $event)
    {
        $this->apply($event);
    }

    protected function applyDelegateRegistered(DelegateRegistered $event)
    {
        $ticket = $event->getTicket();
        $this->delegates[$ticket->getPurchaseId()][$ticket->getTicketId()] = $event->getDelegateId();
    }
}