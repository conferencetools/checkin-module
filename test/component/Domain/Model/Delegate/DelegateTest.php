<?php

namespace ConferenceTools\Checkin\Domain\Model\Delegate;

use Carnage\Cqrs\Testing\AbstractBusTest;
use ConferenceTools\Checkin\Domain\Command\Delegate\RegisterDelegate;
use ConferenceTools\Checkin\Domain\CommandHandler\Delegate as DelegateCommandHandler;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateRegistered;

class DelegateTest extends AbstractBusTest
{
    protected $modelClass = Delegate::class;

    public function testRegister()
    {
        $sut = new DelegateCommandHandler($this->repository, $this->idGenerator);
        $this->setupLogger($sut);

        $message = new RegisterDelegate('ted', 'banks', 'ted.banks@gmail.com', 'pid', 'tid');
        $sut->handle($message);

        self::assertCount(1, $this->messageBus->messages);
        /** @var DelegateRegistered $domainMessage */
        $domainMessage = $this->messageBus->messages[0]->getEvent();

        self::assertInstanceOf(DelegateRegistered::class, $domainMessage);
        self::assertEquals(1, $domainMessage->getDelegateId());
        self::assertEquals('ted', $domainMessage->getFirstName());
        self::assertEquals('banks', $domainMessage->getLastName());
        self::assertEquals('ted.banks@gmail.com', $domainMessage->getEmail());
        self::assertEquals('pid', $domainMessage->getPurchaseId());
        self::assertEquals('tid', $domainMessage->getTicketId());
    }
}