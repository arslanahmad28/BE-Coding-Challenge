<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use App\Service\ServiceImportJob;
use App\Parser\LogsParser;
use App\Parser\ParserInterface;

#[AsCommand(
    name: 'app:import-logs',
    description: 'Import the log records to database from the given file.',
)]
class ImportLogsCommand extends Command
{

    /** @var ConsoleStyle */
    private $io;

    /** @var ServiceImportJob */
    private $serviceImportJob;

    /** @var LogsParser */
    private $logsParser;

    /**
     * __construct
     *
     * @param  ServiceImportJob $serviceImportJob
     * @param  ParserInterface $logsParser
     */
    public function __construct(ServiceImportJob $serviceImportJob, LogsParser $logsParser)
    {
        $this->serviceImportJob = $serviceImportJob;
        $this->logsParser = $logsParser;
        parent::__construct();
    }

    /**
     * configure
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument('logfile', InputArgument::REQUIRED, 'Give the logs file path to import data into database.')
            ->addArgument('batchSize', InputArgument::OPTIONAL, 'Batch Size', 3)
            ->setHelp("The <info>%command.name%</info> import logs data from file to database: <info>php %command.full_name% logs.txt</info>
            If the logfile argument is missing, the command will ask for the command name interactively.");
    }

    /**
     * initialize
     *
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new ConsoleStyle($input, $output);
    }

    /**
     * interact
     *
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return void
     */
    public function interact(InputInterface $input, OutputInterface $output): void
    {
        while (!$input->getArgument('logfile') || !file_exists($input->getArgument('logfile'))) {

            $this->io->error("Log File path cannot be empty or invalid");

            $value = $this->io->ask("Please provide the logs file path", null);
            $input->setArgument("logfile", $value);
        }
    }

    /**
     * execute
     *
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $logfile = $input->getArgument('logfile');
        $batchSize = (int) $input->getArgument('batchSize');

        $response = $this->serviceImportJob->executeImport($batchSize, $logfile, $this->logsParser, "Log");
        $status = $response["status"];
        $this->io->$status($response["msg"]);

        return $status == "success" ? Command::SUCCESS : Command::FAILURE;
    }
}
