<?php

namespace ConferenceTools\Checkin\Domain\ValueObject;

final class Ticket
{
    private $purchaseId;

    private $ticketId;

    public function __construct(string $purchaseId, string $ticketId)
    {
        $this->purchaseId = $purchaseId;
        $this->ticketId = $ticketId;
    }

    public function getPurchaseId(): string
    {
        return $this->purchaseId;
    }

    public function getTicketId(): string
    {
        return $this->ticketId;
    }
}
