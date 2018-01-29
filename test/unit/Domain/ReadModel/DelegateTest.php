<?php

namespace ConferenceTools\Checkin\Domain\ReadModel;

use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;
use PHPUnit\Framework\TestCase;

class DelegateTest extends TestCase
{
    public function testConstruction()
    {
        $delegate = new DelegateInfo('ted', 'banks', 'tb@gmail.com');
        $ticket = new Ticket('pid', 'tid');
        $sut = new Delegate('did', $delegate, $ticket, 'admin@company.com');

        self::assertEquals('did', $sut->getDelegateId());
        self::assertEquals('ted', $sut->getFirstName());
        self::assertEquals('banks', $sut->getLastName());
        self::assertEquals('tb@gmail.com', $sut->getEmail());
        self::assertEquals('admin@company.com', $sut->getPurchaserEmail());
        self::assertEquals('pid', $sut->getPurchaseId());
        self::assertEquals('tid', $sut->getTicketId());
        self::assertFalse($sut->checkedIn(), 'Delegate was constructed as checked in');
    }

    public function testCheckIn()
    {
        $delegate = new DelegateInfo('ted', 'banks', 'tb@gmail.com');
        $ticket = new Ticket('pid', 'tid');
        $sut = new Delegate('did', $delegate, $ticket, 'admin@company.com');

        $sut->checkIn();

        self::assertTrue($sut->checkedIn(), 'Delegate was not marked as checked in');
    }
}
