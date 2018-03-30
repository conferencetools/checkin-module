<?php

namespace ConferenceTools\Checkin\Cli\Command;

use Carnage\Cqrs\MessageBus\MessageBusInterface;
use Carnage\Cqrs\Persistence\ReadModel\RepositoryInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use ConferenceTools\Checkin\Domain\ReadModel\Delegate;
use ConferenceTools\Tickets\Domain\Command\Ticket\CancelTicket as CancelTicketCommand;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateQR extends Command
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    public static function build(RepositoryInterface $repository)
    {
        $instance = new static();
        $instance->repository = $repository;

        return $instance;
    }

    protected function configure()
    {
        $this->setName('checkin:generate-qr')
            ->setDescription('Generates a QR code for all delegates')
            ->setDefinition([
                new InputArgument('outputDir', InputArgument::OPTIONAL, 'Output dir', '/tmp'),
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputDir = $input->getArgument('outputDir');
        if (!is_dir($outputDir) && !(mkdir($outputDir, '0777', true) && is_dir($outputDir))) {
            throw new \Exception('Invalid output directory');
        }
        $count = 0;
        //@TODO inject?
        $renderer = new QRCode(new QROptions(['outputType' => QRCode::OUTPUT_IMAGE_PNG, 'imageBase64' => false]));;

        $delegates = $this->repository->matching(Criteria::create());

        foreach ($delegates as $delegate) {
            /** @var Delegate $delegate */
            $filename = $outputDir . '/' . $delegate->getPurchaseId() . '.' . $delegate->getTicketId() . '.png';
            file_put_contents($filename, $renderer->render($delegate->getTicketId()));
            $count++;
        }


        $output->writeln(sprintf('Generated %d QR codes', $count));
    }
}
