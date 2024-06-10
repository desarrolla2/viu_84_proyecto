<?php

namespace App\Infrastructure\Command\Bench;

use App\Domain\Generator\Entity\Document as GeneratorDocument;
use App\Domain\Generator\Service\GeneratorEngine;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:bench:generator', description: 'Calculate the performance of executing Generator',)]
class GeneratorCommand extends Command
{
    public function __construct(private readonly GeneratorEngine $generatorEngine)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('input', InputArgument::REQUIRED, 'path to file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $inputArgument = $input->getArgument('input');

        $initialMemoryUsage = memory_get_usage();
        $startTime = microtime(true);

        $iterations = 10;
        for ($i = 1; $i <= $iterations; $i++) {
            $output->writeln(sprintf(' - <info>iteration %d</info>', $i));
            $generatorDocument = new GeneratorDocument($inputArgument);
            $text = $this->generatorEngine->execute($generatorDocument);
        }
        $memoryUsage = (memory_get_usage() - $initialMemoryUsage) / $iterations;
        $timeUsage = (microtime(true) - $startTime) / $iterations;

        $table = new Table($output);
        $table->setHeaders(['Key', 'Value',]);
        $table->setRows([['Time', $timeUsage], ['Memory', $memoryUsage]]);

        $table->render();

        return Command::SUCCESS;
    }
}
