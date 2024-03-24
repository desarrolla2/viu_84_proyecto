<?php

namespace App\Domain\Reader\Service;

use App\Domain\Reader\Entity\AgreementInterface;
use App\Domain\Reader\ValueObject\Text;
use Psr\Log\LoggerInterface;

class ReaderEngine
{
    private array $processors = [];
    private readonly ?LoggerInterface $logger;

    public function __construct(LoggerInterface $readerLogger)
    {
        $this->logger = $readerLogger;
    }

    public function addProcessor(ProcessorInterface $processor): void
    {
        $this->processors[] = $processor;
    }

    public function addProcessors($processors): void
    {
        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }
    }

    public function execute(Text $document): AgreementInterface
    {
        return $this->executeProcessor($document);
    }

    private function executeProcessor(Text $document): AgreementInterface
    {
        $processor = $this->getProcessor($document);
        if (!$processor) {
            throw new \RuntimeException();
        }

        $this->log(sprintf('Executing Processor "%s"', get_class($processor)));

        return $processor->execute($document);
    }

    private function getProcessor(Text $document): ?ProcessorInterface
    {
        $processors = [];
        /** @var ProcessorInterface $processor */
        foreach ($this->processors as $processor) {
            $score = $processor->score($document);
            $this->log(sprintf('Scored Processor "%s" with "%s"', get_class($processor), number_format($score, 0)));
            if (!$score) {
                continue;
            }
            $processors[] = ['score' => $score, 'processor' => $processor];
        }
        usort($processors, function (array $processor1, array $processor2) {
            return $processor2['score'] <=> $processor1['score'];
        });

        return reset($processors)['processor'];
    }

    private function log(string $message, array $context = []): void
    {
        if (!$this->logger) {
            return;
        }
        $this->logger->info($message, $context);
    }
}
