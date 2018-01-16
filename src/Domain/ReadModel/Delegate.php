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

    public function __construct(string $delegateId, DelegateInfo $delegateInfo, Ticket $ticket)
    {
        $this->delegateId = $delegateId;
        $this->firstName = $delegateInfo->getFirstName();
        $this->lastName = $delegateInfo->getLastName();
        $this->email = $delegateInfo->getEmail();
        $this->purchaseId = $ticket->getPurchaseId();
        $this->ticketId = $ticket->getTicketId();
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
}