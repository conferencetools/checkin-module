<?php

namespace ConferenceTools\Checkin\Domain\Event\Purchase;

use Carnage\Cqrs\Event\EventInterface;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;
use JMS\Serializer\Annotation as JMS;

class TicketCreated implements EventInterface
{
    /**
     * @var Ticket
     * @JMS\Type("ConferenceTools\Checkin\Domain\ValueObject\Ticket")
     */
    private $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function getTicket(): Ticket
    {
        return $this->ticket;
    }
}
