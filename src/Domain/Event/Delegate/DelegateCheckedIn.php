<?php

namespace ConferenceTools\Checkin\Domain\Event\Delegate;

use Carnage\Cqrs\Event\EventInterface;
use JMS\Serializer\Annotation as JMS;

class DelegateCheckedIn implements EventInterface
{
    /**
     * @var string
     * @JMS\Type("string")
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
