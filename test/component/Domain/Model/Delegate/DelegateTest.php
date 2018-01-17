<?php

namespace ConferenceTools\Checkin\Domain\Model\Delegate;

use Carnage\Cqrs\Testing\AbstractBusTest;
use ConferenceTools\Checkin\Domain\Command\Delegate\RegisterDelegate;
use ConferenceTools\Checkin\Domain\CommandHandler\Delegate as DelegateCommandHandler;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateRegistered;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;

class DelegateTest extends AbstractBusTest
{
    protected $modelClass = Delegate::class;

    public function testRegister()
    {
        $sut = new DelegateCommandHandler($this->repository, $this->idGenerator);
        $this->setupLogger($sut);

        $delegate = new DelegateInfo('ted', 'banks', 'ted.banks@gmail.com');
        $ticket = new Ticket('pid', 'tid');
        $message = new RegisterDelegate($delegate, $ticket, 'admin@company.com');
        $sut->handle($message);

        self::assertCount(1, $this->messageBus->messages);
        /** @var DelegateRegistered $domainMessage */
        $domainMessage = $this->messageBus->messages[0]->getEvent();

        self::assertInstanceOf(DelegateRegistered::class, $domainMessage);
        self::assertEquals(1, $domainMessage->getDelegateId());
        self::assertSame($delegate, $domainMessage->getDelegateInfo());
        self::assertSame($ticket, $domainMessage->getTicket());
        self::assertEquals('admin@company.com', $domainMessage->getPurchaserEmail());
    }
}