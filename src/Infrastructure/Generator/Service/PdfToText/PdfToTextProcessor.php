<?php

namespace App\Infrastructure\Generator\Service\PdfToText;

use App\Domain\Component\CommandRunner\CommandRunnerInterface;
use App\Domain\Generator\Entity\Document;
use App\Domain\Generator\Service\ProcessorInterface;
use App\Domain\Generator\ValueObject\Text;

class PdfToTextProcessor implements ProcessorInterface
{
    private const PDF_TO_TEXT = 'pdftotext';

    public function __construct(private readonly CommandRunnerInterface $commandRunner)
    {
    }

    public function execute(Document $document): Text
    {
        $output = sprintf('%s/%s.txt', sys_get_temp_dir(), hash('sha256', uniqid(get_called_class(), true)));

        $this->commandRunner->run([self::PDF_TO_TEXT, $document->path(), $output]);

        $content = file_get_contents($output);

        $this->commandRunner->run(['rm', '-rf', $output]);

        return new Text($content);
    }

    public function score(Document $document): int
    {
        return 50;
    }
}
