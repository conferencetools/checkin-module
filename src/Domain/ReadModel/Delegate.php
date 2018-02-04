<?php

namespace ConferenceTools\Checkin\Domain\ReadModel;

use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Delegate
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $delegateId;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $firstName;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $lastName;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $email;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $purchaseId;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $ticketId;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $purchaserEmail;
    /**
     * @var string
     * @ORM\Column(type="boolean")
     */
    private $checkedIn = false;

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

    public function checkIn(): void
    {
        $this->checkedIn = true;
    }

    public function checkedIn(): bool
    {
        return $this->checkedIn;
    }
}