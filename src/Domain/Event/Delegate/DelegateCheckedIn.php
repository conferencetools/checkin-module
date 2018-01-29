<?php

namespace ConferenceTools\Checkin\Domain\Event\Delegate;

use Carnage\Cqrs\Event\EventInterface;

class DelegateCheckedIn implements EventInterface
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
