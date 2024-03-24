<?php

namespace App\Infrastructure\Command;

use App\Domain\Generator\Entity\Document as GeneratorDocument;
use App\Domain\Generator\Service\GeneratorEngine;
use App\Domain\Reader\Entity\DummyAgreement;
use App\Domain\Reader\Service\ReaderEngine;
use App\Domain\Reader\ValueObject\Text as ReaderText;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:run', description: 'extracts the content of a PDF document and if it is contract, indicates the parties involved',)]
class AppCommand extends Command
{
    public function __construct(private readonly GeneratorEngine $generatorEngine, private readonly ReaderEngine $readerEngine)
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

        $readerText = new ReaderText($text->content());
        $agreement = $this->readerEngine->execute($readerText);
        if ($agreement instanceof DummyAgreement) {
            $io->writeln('<error>error</error>: document type could not be determined');

            return Command::SUCCESS;
        }

        $table = new Table($output);
        $table->setHeaders(['Key', 'Value',]);
        $table->addRow(['Document', (new \ReflectionClass($agreement))->getShortName()]);
        foreach ($agreement->parties() as $person) {
            $table->addRow(['Party', sprintf('%s, %s', $person->name(), $person->number())]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
