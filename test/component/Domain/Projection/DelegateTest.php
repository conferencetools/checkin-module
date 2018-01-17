<?php

namespace ConferenceTools\Checkin\Domain\Projection;


use Carnage\Cqrs\Persistence\ReadModel\InMemoryRepository;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateRegistered;
use ConferenceTools\Checkin\Domain\ReadModel\Delegate as DelegateReadModel;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;
use PHPUnit\Framework\TestCase;
use Zend\Log\Logger;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\Writer\Noop;

class TaskTest extends TestCase
{
    public function test_a_task_should_be_created()
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

    protected function setupLogger(LoggerAwareInterface $handler)
    {
        $logger = (new Logger())->addWriter(new Noop());

        $handler->setLogger($logger);
    }
}
