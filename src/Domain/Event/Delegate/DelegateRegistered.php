<?php


namespace ConferenceTools\Checkin\Domain\Event\Delegate;

use Carnage\Cqrs\Event\EventInterface;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;

class DelegateRegistered implements EventInterface
{
    /**
     * @var string
     */
    private $delegateId;
    /**
     * @var DelegateInfo
     */
    private $delegateInfo;
    /**
     * @var Ticket
     */
    private $ticket;

    public function __construct(
        string $delegateId,
        DelegateInfo $delegateInfo,
        Ticket $ticket
    ) {
        $this->delegateId = $delegateId;
        $this->delegateInfo = $delegateInfo;
        $this->ticket = $ticket;
    }

    public function getDelegateId(): string
    {
        return $this->delegateId;
    }

    public function getDelegateInfo(): DelegateInfo
    {
        return $this->delegateInfo;
    }

    public function getTicket(): Ticket
    {
        return $this->ticket;
    }
}
