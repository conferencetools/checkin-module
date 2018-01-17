<?php

namespace ConferenceTools\Checkin\Domain\Event\Purchase;

use Carnage\Cqrs\Event\EventInterface;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;

class TicketPurchasePaid implements EventInterface
{
    /**
     * @var string
     */
    private $purchaseId;
    /**
     * @var string
     */
    private $purchaserEmail;

    public function __construct(
        string $purchaseId,
        string $purchaserEmail
    ) {
        $this->purchaserEmail = $purchaserEmail;
        $this->purchaseId = $purchaseId;
    }

    public function getPurchaserEmail(): string
    {
        return $this->purchaserEmail;
    }

    /**
     * @return string
     */
    public function getPurchaseId(): string
    {
        return $this->purchaseId;
    }
}
