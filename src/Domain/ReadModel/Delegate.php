<?php

namespace ConferenceTools\Checkin\Domain\ReadModel;

use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;

class Delegate
{
    private $id;

    private $delegateId;

    private $firstName;

    private $lastName;

    private $email;

    private $purchaseId;

    private $ticketId;
    /**
     * @var string
     */
    private $purchaserEmail;

    public function __construct(string $delegateId, DelegateInfo $delegateInfo, Ticket $ticket, string $purchaserEmail)
    {
        $this->delegateId = $delegateId;
        $this->firstName = $delegateInfo->getFirstName();
        $this->lastName = $delegateInfo->getLastName();
        $this->email = $delegateInfo->getEmail();
        $this->purchaseId = $ticket->getPurchaseId();
        $this->ticketId = $ticket->getTicketId();
        $this->purchaserEmail = $purchaserEmail;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDelegateId(): string
    {
        return $this->delegateId;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPurchaseId(): string
    {
        return $this->purchaseId;
    }

    public function getTicketId(): string
    {
        return $this->ticketId;
    }

    public function getPurchaserEmail(): string
    {
        return $this->purchaserEmail;
    }

    public function updateDelegateInfo(DelegateInfo $delegateInfo)
    {
        $this->firstName = $delegateInfo->getFirstName();
        $this->lastName = $delegateInfo->getLastName();
        $this->email = $delegateInfo->getEmail();
    }
}