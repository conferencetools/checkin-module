<?php

namespace ConferenceTools\Checkin\Domain\Command\Delegate;

use Carnage\Cqrs\Command\CommandInterface;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use JMS\Serializer\Annotation as JMS;

class UpdateDelegateInformation implements CommandInterface
{
    /**
     * @var DelegateInfo
     * @JMS\Type("ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo")
     */
    private $delegateInfo;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $delegateId;

    public function __construct(
        string $delegateId,
        DelegateInfo $delegateInfo
    ) {
        $this->delegateInfo = $delegateInfo;
        $this->delegateId = $delegateId;
    }

    public function getDelegateInfo(): DelegateInfo
    {
        return $this->delegateInfo;
    }

    public function getDelegateId(): string
    {
        return $this->delegateId;
    }
}