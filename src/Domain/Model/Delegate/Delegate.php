<?php


namespace ConferenceTools\Checkin\Domain\Model\Delegate;


use Carnage\Cqrs\Aggregate\AbstractAggregate;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateRegistered;

class Delegate extends AbstractAggregate
{
    private $id;

    public function getId()
    {
        return $this->id;
    }

    public static function register(
        string $id,
        string $firstName,
        string $lastName,
        string $email,
        string $purchaseId,
        string $ticketId
    ) {
        $instance = new static();
        $instance->apply(new DelegateRegistered($id, $firstName, $lastName, $email, $purchaseId, $ticketId));

        return $instance;
    }

    protected function applyDelegateRegistered(DelegateRegistered $event)
    {
        $this->id = $event->getDelegateId();
    }
}