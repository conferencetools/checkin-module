<?php

namespace ConferenceTools\Checkin\Domain\Event\Purchase;

use Carnage\Cqrs\Event\EventInterface;
use JMS\Serializer\Annotation as JMS;

class TicketPurchasePaid implements EventInterface
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
