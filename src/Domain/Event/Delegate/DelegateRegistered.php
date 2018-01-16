<?php


namespace ConferenceTools\Checkin\Domain\Event\Delegate;

use Carnage\Cqrs\Event\EventInterface;

class DelegateRegistered implements EventInterface
{
    /**
     * @var string
     */
    private $delegateId;
    /**
     * @var string
     */
    private $firstName;
    /**
     * @var string
     */
    private $lastName;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $purchaseId;
    /**
     * @var string
     */
    private $ticketId;

    public function __construct(
        string $delegateId,
        string $firstName,
        string $lastName,
        string $email,
        string $purchaseId,
        string $ticketId
    ) {
        $this->delegateId = $delegateId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->purchaseId = $purchaseId;
        $this->ticketId = $ticketId;
    }

    /**
     * @return string
     */
    public function getDelegateId(): string
    {
        return $this->delegateId;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPurchaseId(): string
    {
        return $this->purchaseId;
    }

    /**
     * @return string
     */
    public function getTicketId(): string
    {
        return $this->ticketId;
    }
}
