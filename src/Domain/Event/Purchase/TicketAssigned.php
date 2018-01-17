<?php

namespace ConferenceTools\Checkin\Domain\Event\Purchase;

use Carnage\Cqrs\Event\EventInterface;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;

class TicketAssigned implements EventInterface
{
    /**
     * @var DelegateInfo
     */
    private $delegateInfo;
    /**
     * @var Ticket
     */
    private $ticket;

    public function __construct(
        DelegateInfo $delegateInfo,
        Ticket $ticket
    ) {
        $this->delegateInfo = $delegateInfo;
        $this->ticket = $ticket;
    }

    public function getDelegateInfo(): DelegateInfo
    {
        return $this->delegateInfo;
    }

    public function getTicket(): Ticket
    {
        return $this->ticket;
    }
}
