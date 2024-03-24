<?php

namespace App\Infrastructure\Command;

use App\Domain\Generator\Entity\Document;
use App\Domain\Generator\Service\GeneratorEngine;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:generator:run', description: 'Extracts the content of a PDF document and transforms it into text',)]
class GeneratorCommand extends Command
{
    public function __construct(private readonly GeneratorEngine $engine)
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

        $document = new Document($inputArgument);
        $text = $this->engine->execute($document);

        $io->text($text->content());

        return Command::SUCCESS;
    }
}
