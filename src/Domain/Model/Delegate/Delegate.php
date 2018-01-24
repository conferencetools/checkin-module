<?php


namespace ConferenceTools\Checkin\Domain\Model\Delegate;

use Carnage\Cqrs\Aggregate\AbstractAggregate;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateInformationUpdated;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateRegistered;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;

class Delegate extends AbstractAggregate
{
    private $id;
    private $delegateInfo;

    public function getId()
    {
        return $this->id;
    }

    public static function register(
        string $id,
        DelegateInfo $delegateInfo,
        Ticket $ticket,
        string $purchaserEmail
    ) {
        $instance = new static();
        $instance->apply(new DelegateRegistered($id, $delegateInfo, $ticket, $purchaserEmail));

        return $instance;
    }

    protected function applyDelegateRegistered(DelegateRegistered $event)
    {
        $this->id = $event->getDelegateId();
        $this->delegateInfo = $event->getDelegateInfo();
    }

    public function updateDelegateInformation(DelegateInfo $delegateInfo)
    {
        $this->apply(new DelegateInformationUpdated($this->id, $delegateInfo));
    }

    protected function applyDelegateInformationUpdated(DelegateInformationUpdated $event)
    {
        $this->delegateInfo = $event->getDelegateInfo();
    }
}