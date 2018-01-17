<?php

namespace ConferenceTools\Checkin\Domain\Command\Delegate;

use Carnage\Cqrs\Command\CommandInterface;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;

class RegisterDelegate implements CommandInterface
{
    /**
     * @var DelegateInfo
     */
    private $delegateInfo;
    /**
     * @var Ticket
     */
    private $ticket;

    private $purchaserEmail;

    public function __construct(
        DelegateInfo $delegateInfo,
        Ticket $ticket,
        string $purchaserEmail
    ) {
        $this->delegateInfo = $delegateInfo;
        $this->ticket = $ticket;
        $this->purchaserEmail = $purchaserEmail;
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