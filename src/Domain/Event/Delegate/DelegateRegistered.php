<?php


namespace ConferenceTools\Checkin\Domain\Event\Delegate;

use Carnage\Cqrs\Event\EventInterface;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;

class DelegateRegistered implements EventInterface
{
    /**
     * @var string
     */
    private $delegateId;
    /**
     * @var DelegateInfo
     */
    private $delegateInfo;
    /**
     * @var Ticket
     */
    private $ticket;
    /**
     * @var string
     */
    private $purchaserEmail;

    public function __construct(
        string $delegateId,
        DelegateInfo $delegateInfo,
        Ticket $ticket,
        string $purchaserEmail
    ) {
        $this->delegateId = $delegateId;
        $this->delegateInfo = $delegateInfo;
        $this->ticket = $ticket;
        $this->purchaserEmail = $purchaserEmail;
    }

    public function getDelegateId(): string
    {
        return $this->delegateId;
    }

    public function getDelegateInfo(): DelegateInfo
    {
        return $this->delegateInfo;
    }

    public function getTicket(): Ticket
    {
        return $this->ticket;
    }

    public function getPurchaserEmail(): string
    {
        return $this->purchaserEmail;
    }
}
