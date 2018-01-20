<?php

namespace ConferenceTools\Checkin\Domain\Command\Delegate;

use Carnage\Cqrs\Command\CommandInterface;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;

class UpdateDelegateInformation implements CommandInterface
{
    /**
     * @var DelegateInfo
     */
    private $delegateInfo;
    /**
     * @var string
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