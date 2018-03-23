<?php

namespace ConferenceTools\Checkin\Domain\Process;

use Carnage\Cqrs\Testing\AbstractBusTest;
use ConferenceTools\Checkin\Domain\Command\Delegate\RegisterDelegate;
use ConferenceTools\Checkin\Domain\Command\Delegate\UpdateDelegateInformation;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateRegistered;
use ConferenceTools\Checkin\Domain\Event\Purchase\TicketAssigned;
use ConferenceTools\Checkin\Domain\Event\Purchase\TicketPurchasePaid;
use ConferenceTools\Checkin\Domain\ProcessManager\ImportPurchase as ImportPurchaseProcessManager;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;

class ImportPurchaseTest extends AbstractBusTest
{
    protected $modelClass = ImportPurchase::class;

    public function testTicketAssignedProducesNoCommands()
    {
        $sut = new ImportPurchaseProcessManager($this->repository);
        $this->setupLogger($sut);

        $delegate = new DelegateInfo('ted', 'banks', 'ted.banks@gmail.com');
        $ticket = new Ticket('pid', 'tid');
        $message = new TicketAssigned($delegate, $ticket);
        $sut->handle($message);

        self::assertCount(1, $this->messageBus->messages);
        $domainMessage = $this->messageBus->messages[0]->getEvent();

        self::assertSame($message, $domainMessage);
    }

    public function testTicketPurchasePaidProducesNoCommands()
    {
        $sut = new ImportPurchaseProcessManager($this->repository);
        $this->setupLogger($sut);

        $message = new TicketPurchasePaid('pid', 'admin@company.com');
        $sut->handle($message);

        self::assertCount(1, $this->messageBus->messages);
        $domainMessage = $this->messageBus->messages[0]->getEvent();

        self::assertSame($message, $domainMessage);
    }

    public function testTicketAssignedAndPurchasePaidRegistersADelegate()
    {
        $sut = new ImportPurchaseProcessManager($this->repository);
        $this->setupLogger($sut);

        $delegate = new DelegateInfo('ted', 'banks', 'ted.banks@gmail.com');
        $ticket = new Ticket('pid', 'tid');
        $message = new TicketAssigned($delegate, $ticket);
        $sut->handle($message);

        $message = new TicketPurchasePaid('pid', 'admin@company.com');
        $sut->handle($message);

        self::assertCount(3, $this->messageBus->messages);
        $domainMessage = $this->messageBus->messages[2]->getEvent();

        $delegate = new DelegateInfo('ted', 'banks', 'ted.banks@gmail.com');
        $ticket = new Ticket('pid', 'tid');
        $expected = new RegisterDelegate($delegate, $ticket, 'admin@company.com');

        self::assertEquals($expected, $domainMessage);
    }

    public function testPurchasePaidAndTicketAssignedRegistersADelegate()
    {
        $sut = new ImportPurchaseProcessManager($this->repository);
        $this->setupLogger($sut);

        $message = new TicketPurchasePaid('pid', 'admin@company.com');
        $sut->handle($message);

        $delegate = new DelegateInfo('ted', 'banks', 'ted.banks@gmail.com');
        $ticket = new Ticket('pid', 'tid');
        $message = new TicketAssigned($delegate, $ticket);
        $sut->handle($message);

        self::assertCount(3, $this->messageBus->messages);
        $domainMessage = $this->messageBus->messages[2]->getEvent();

        $delegate = new DelegateInfo('ted', 'banks', 'ted.banks@gmail.com');
        $ticket = new Ticket('pid', 'tid');
        $expected = new RegisterDelegate($delegate, $ticket, 'admin@company.com');

        self::assertEquals($expected, $domainMessage);
    }

    public function testUpdateDelegateInfo()
    {
        $sut = new ImportPurchaseProcessManager($this->repository);
        $this->setupLogger($sut);

        $delegate = new DelegateInfo('ted', 'banks', 'ted.banks@gmail.com');
        $ticket = new Ticket('pid', 'tid');
        $message = new TicketAssigned($delegate, $ticket);
        $sut->handle($message);

        $message = new TicketPurchasePaid('pid', 'admin@company.com');
        $sut->handle($message);

        $sut->handle(new DelegateRegistered('did', $delegate, $ticket, 'admin@company.com'));

        $delegate = new DelegateInfo('ben', 'franks', 'ben.franks@gmail.com');
        $ticket = new Ticket('pid', 'tid');
        $message = new TicketAssigned($delegate, $ticket);
        $sut->handle($message);

        self::assertCount(5, $this->messageBus->messages);
        $domainMessage = $this->messageBus->messages[4]->getEvent();

        $delegate = new DelegateInfo('ben', 'franks', 'ben.franks@gmail.com');
        $expected = new UpdateDelegateInformation('did', $delegate);

        self::assertEquals($expected, $domainMessage);
    }
}