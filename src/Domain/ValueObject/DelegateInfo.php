<?php

namespace ConferenceTools\Checkin\Domain\ValueObject;
use JMS\Serializer\Annotation as JMS;

final class DelegateInfo
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $firstName;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $lastName;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $email;

    public function __construct(string $firstName, string $lastName, string $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
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
}
