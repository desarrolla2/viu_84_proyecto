<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Infrastructure\Command;

use App\Domain\Generator\Entity\Document as GeneratorDocument;
use App\Domain\Generator\Service\GeneratorEngine;
use App\Domain\Reader\Entity\AgreementInterface;
use App\Domain\Reader\Entity\DummyAgreementInterface;
use App\Domain\Reader\Service\ReaderEngine;
use App\Domain\Reader\ValueObject\Text as ReaderText;
use ReflectionClass;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(name: 'app:run', description: 'extracts the content of a PDF document and if it is contract, indicates the parties involved',)]
class AppCommand extends Command
{
    public function __construct(private readonly GeneratorEngine $generatorEngine, private readonly ReaderEngine $readerEngine, private readonly SerializerInterface $serializer)
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
        if ($agreement instanceof DummyAgreementInterface) {
            $io->writeln('<error>error</error>: document type could not be determined');

            return Command::SUCCESS;
        }

        $response = $this->createResponse($agreement);
        $rows = $this->formatArrayForTable($response);

        $table = new Table($output);
        $table->setHeaders(['Key', 'Value',]);
        $table->setRows($rows);

        $table->render();

        return Command::SUCCESS;
    }

    private function createResponse(AgreementInterface $agreement): array
    {
        $content = $this->serializer->serialize($agreement, 'json');

        return [
            'code' => Response::HTTP_OK,
            'type_of_document' => (new ReflectionClass($agreement))->getShortName(),
            'content' => json_decode($content, true),
        ];
    }

    private function formatArrayForTable(array $array, string $prefix = ''): array
    {
        $rows = [];
        foreach ($array as $key => $value) {
            $fullKey = $prefix ? "{$prefix}.{$key}" : $key;
            if (is_array($value)) {
                $rows = array_merge($rows, $this->formatArrayForTable($value, $fullKey));
            } else {
                $rows[] = [$fullKey, $value];
            }
        }

        return $rows;
    }
}
