<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Infrastructure\Command\Bench;

use App\Domain\Generator\Entity\Document as GeneratorDocument;
use App\Domain\Generator\Service\GeneratorEngine;
use App\Domain\Reader\Service\ReaderEngine;
use App\Domain\Reader\ValueObject\Text as ReaderText;
use App\Infrastructure\Component\HttpClient\SymfonyHttpClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:bench:reader', description: 'Calculate the performance of executing Reader',)]
class ReaderCommand extends Command
{
    public function __construct(private readonly GeneratorEngine $generatorEngine, private readonly ReaderEngine $readerEngine, private SymfonyHttpClient $httpClient,)
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

        $generatorDocument = new GeneratorDocument($inputArgument);
        $text = $this->generatorEngine->execute($generatorDocument);

        $this->httpClient->setTtl(-1);

        $initialMemoryUsage = memory_get_usage();
        $startTime = microtime(true);

        $iterations = 4;
        for ($i = 1; $i <= $iterations; $i++) {
            $output->writeln(sprintf(' - <info>iteration %d</info>', $i));
            $readerText = new ReaderText($text->content());
            $agreement = $this->readerEngine->execute($readerText);
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
