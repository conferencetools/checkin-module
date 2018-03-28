<?php

namespace ConferenceTools\Checkin\Domain\Process;

use Carnage\Cqrs\Testing\AbstractBusTest;
use ConferenceTools\Checkin\Domain\Command\Delegate\RegisterDelegate;
use ConferenceTools\Checkin\Domain\Command\Delegate\UpdateDelegateInformation;
use ConferenceTools\Checkin\Domain\Event\Delegate\DelegateRegistered;
use ConferenceTools\Checkin\Domain\Event\Purchase\TicketAssigned;
use ConferenceTools\Checkin\Domain\Event\Purchase\TicketCreated;
use ConferenceTools\Checkin\Domain\Event\Purchase\TicketPurchasePaid;
use ConferenceTools\Checkin\Domain\ProcessManager\ImportPurchase as ImportPurchaseProcessManager;
use ConferenceTools\Checkin\Domain\ValueObject\DelegateInfo;
use ConferenceTools\Checkin\Domain\ValueObject\Ticket;

class ImportPurchaseTest extends AbstractBusTest
{
    protected $modelClass = ImportPurchase::class;
    private $delegates;
    private $ticket;
    private $events;
    private $commands;

    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->delegates['ted banks'] = new DelegateInfo('ted', 'banks', 'ted.banks@gmail.com');
        $this->delegates['ben franks'] = new DelegateInfo('ben', 'franks', 'ben.franks@gmail.com');
        $this->delegates['placeholder'] = new DelegateInfo('', '', '');
        $this->ticket = new Ticket('pid', 'tid');

        $this->events['assigned to ted banks'] =  new TicketAssigned($this->delegates['ted banks'], $this->ticket);
        $this->events['purchase paid by admin'] = new TicketPurchasePaid('pid', 'admin@company.com');
        $this->events['ted banks registered'] = new DelegateRegistered('did', $this->delegates['ted banks'], $this->ticket, 'admin@company.com');
        $this->events['placeholder delegate registered'] = new DelegateRegistered('did', $this->delegates['placeholder'], $this->ticket, 'admin@company.com');
        $this->events['assigned to ben franks'] =  new TicketAssigned($this->delegates['ben franks'], $this->ticket);
        $this->events['ticket created'] = new TicketCreated($this->ticket);

        $this->commands['register delegate ted banks'] = new RegisterDelegate($this->delegates['ted banks'], $this->ticket, 'admin@company.com');
        $this->commands['register placeholder delegate'] = new RegisterDelegate($this->delegates['placeholder'], $this->ticket, 'admin@company.com');
        $this->commands['update delegate ben franks'] = new UpdateDelegateInformation('did', $this->delegates['ben franks']);
    }

    /**
     * @dataProvider provideTestProcess
     * @param array $given
     * @param $when
     * @param int $expectedCount
     * @param array $expect
     */
    public function testProcess(array $given, $when, int $expectedCount, array $expect)
    {
        $sut = new ImportPurchaseProcessManager($this->repository);
        $this->setupLogger($sut);

        foreach ($given as $message) {
            $sut->handle($message);
        }

        $sut->handle($when);

        self::assertCount($expectedCount, $this->messageBus->messages);

        $startFrom = count($this->messageBus->messages) - count($expect);
        foreach ($expect as $index => $expected) {
            self::assertEquals($expected, $this->messageBus->messages[$startFrom + $index]->getEvent());
        }
    }

    public function provideTestProcess()
    {
        return [
            'when a ticket is assigned do nothing' => [
                [],
                $this->events['assigned to ted banks'],
                1,
                []
            ],
            'when a purchase is paid do nothing' => [
                [],
                $this->events['purchase paid by admin'],
                1,
                []
            ],
            'when a ticket is created, do nothing' => [
                [],
                $this->events['ticket created'],
                1,
                []
            ],
            'when a ticket has been assigned and a purchase is paid, register a delegate' => [
                [$this->events['assigned to ted banks']],
                $this->events['purchase paid by admin'],
                3,
                [$this->commands['register delegate ted banks']]
            ],
            'when a ticket is created and has been paid, register a place holder delegate' => [
                [$this->events['ticket created']],
                $this->events['purchase paid by admin'],
                3,
                [$this->commands['register placeholder delegate']]
            ],
            'when a placeholder delegate has been registered and details are provided, update the delegate' => [
                [$this->events['ticket created'], $this->events['purchase paid by admin'], $this->events['placeholder delegate registered']],
                $this->events['assigned to ben franks'],
                5,
                [$this->commands['update delegate ben franks']]
            ],
            'when a delegate has been registered and their information is changed, update the delegate' => [
                [$this->events['assigned to ted banks'], $this->events['purchase paid by admin'], $this->events['ted banks registered']],
                $this->events['assigned to ben franks'],
                5,
                [$this->commands['update delegate ben franks']]
            ]
        ];
    }
}