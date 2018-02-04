<?php

namespace ConferenceTools\Checkin\Domain\ValueObject;
use JMS\Serializer\Annotation as JMS;

final class Ticket
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $purchaseId;
    /**
     * @var string
     * @JMS\Type("string")
     */
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
