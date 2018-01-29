<?php

namespace ConferenceTools\Checkin\Domain\Command\Delegate;

use Carnage\Cqrs\Command\CommandInterface;

class CheckInDelegate implements CommandInterface
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
