<?php


namespace ConferenceTools\Checkin\Domain\Command\Delegate;


use Carnage\Cqrs\Command\CommandInterface;

class RegisterDelegate implements CommandInterface
{
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
        string $firstName,
        string $lastName,
        string $email,
        string $purchaseId,
        string $ticketId
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->purchaseId = $purchaseId;
        $this->ticketId = $ticketId;
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