<?php

namespace ConferenceTools\Checkin\Domain\Event\Delegate;

use Carnage\Cqrs\Event\EventInterface;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;

class DelegateInformationUpdated implements EventInterface
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var DelegateInfo
     */
    private $delegateInfo;

    public function __construct(string $id, DelegateInfo $delegateInfo)
    {
        $this->id = $id;
        $this->delegateInfo = $delegateInfo;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDelegateInfo(): DelegateInfo
    {
        return $this->delegateInfo;
    }
}
