<?php

namespace ConferenceTools\Checkin\Domain\Projection;


use Carnage\Cqrs\Persistence\ReadModel\InMemoryRepository;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateInformationUpdated;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateRegistered;
use ConferenceTools\Checkin\Domain\ReadModel\Delegate as DelegateReadModel;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;
use PHPUnit\Framework\TestCase;
use Zend\Log\Logger;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\Writer\Noop;

class DelegateTest extends TestCase
{
    public function testDelegateRegistered()
    {
        $delegate = new DelegateInfo('ted', 'banks', 'tb@gmail.com');
        $ticket = new Ticket('pid', 'tid');
        $repository = new InMemoryRepository();

        $sut = new Delegate($repository);
        $this->setupLogger($sut);

        $sut->handle(new DelegateRegistered('did', $delegate, $ticket, 'admin@company.com'));

        $expected = new DelegateReadModel('did', $delegate, $ticket, 'admin@company.com');
        self::assertEquals($expected, $repository->get(0));
    }

    public function testUpdateDelegateInformation()
    {
        $delegate = new DelegateInfo('ted', 'banks', 'tb@gmail.com');
        $ticket = new Ticket('pid', 'tid');
        $existing = new DelegateReadModel('did', $delegate, $ticket, 'admin@company.com');

        $repository = new InMemoryRepository();
        $repository->add($existing);

        $sut = new Delegate($repository);
        $this->setupLogger($sut);

        $delegate = new DelegateInfo('fred', 'franks', 'fred.franks@gmail.com');
        $sut->handle(new DelegateInformationUpdated('did', $delegate));

        /** @var DelegateReadModel $result */
        $result = $repository->get(0);
        self::assertEquals('fred', $result->getFirstName());
        self::assertEquals('franks', $result->getLastName());
        self::assertEquals('fred.franks@gmail.com', $result->getEmail());
    }

    protected function setupLogger(LoggerAwareInterface $handler)
    {
        $logger = (new Logger())->addWriter(new Noop());

        $handler->setLogger($logger);
    }
}
