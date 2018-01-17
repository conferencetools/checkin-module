<?php

namespace ConferenceTools\Checkin\Domain\Process;

use Carnage\Cqrs\Aggregate\AbstractAggregate;
use Carnage\Cqrs\Process\NewProcessInterface;
use ConferenceTools\Checkin\Domain\Command\Delegate\RegisterDelegate;
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

    public function ticketAssigned(TicketAssigned $event)
    {
        $this->apply($event);
    }

    protected function applyTicketAssigned(TicketAssigned $event)
    {
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
        $this->paid = true;
        $this->purchaserEmail = $event->getPurchaserEmail();
    }

    protected function applyRegisterDelegate(RegisterDelegate $command)
    {
        array_shift($this->tickets);
    }
}