<?php


namespace ConferenceTools\Checkin\Domain\Model\Delegate;


use Carnage\Cqrs\Aggregate\AbstractAggregate;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateRegistered;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;

class Delegate extends AbstractAggregate
{
    private $id;

    public function getId()
    {
        return $this->id;
    }

    public static function register(
        string $id,
        DelegateInfo $delegateInfo,
        Ticket $ticket
    ) {
        $instance = new static();
        $instance->apply(new DelegateRegistered($id, $delegateInfo, $ticket));

        return $instance;
    }

    protected function applyDelegateRegistered(DelegateRegistered $event)
    {
        $this->id = $event->getDelegateId();
    }
}