<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Domain\Generator\Service;

use App\Domain\Generator\Entity\Document;
use App\Domain\Generator\ValueObject\Text;
use Psr\Log\LoggerInterface;

class GeneratorEngine
{
    /* @var PreProcessorInterface[] $preProcessors */
    private array $preProcessors = [];
    /* @var ProcessorInterface[] $processors */
    private array $processors = [];
    /* @var PostProcessorInterface[] $postProcessors */
    private array $postProcessors = [];
    private readonly ?LoggerInterface $logger;

    public function __construct(LoggerInterface $generatorLogger)
    {
        $this->logger = $generatorLogger;
    }

    public function addPostProcessor(PostProcessorInterface $postProcessor): void
    {
        $this->postProcessors[] = $postProcessor;
    }

    /* @param PostProcessorInterface[] $postProcessors */
    public function addPostProcessors(iterable $postProcessors): void
    {
        foreach ($postProcessors as $postProcessor) {
            $this->addPostProcessor($postProcessor);
        }
    }

    public function addPreProcessor(PreProcessorInterface $preProcessor): void
    {
        $this->preProcessors[] = $preProcessor;
    }

    /* @param PreProcessorInterface[] $preProcessors */
    public function addPreProcessors(iterable $preProcessors): void
    {
        foreach ($preProcessors as $preProcessor) {
            $this->addPreProcessor($preProcessor);
        }
    }

    public function addProcessor(ProcessorInterface $processor): void
    {
        $this->processors[] = $processor;
    }

    /* @param ProcessorInterface[] $processors */
    public function addProcessors(iterable $processors): void
    {
        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }
    }

    public function execute(Document $document): Text
    {
        $this->executePreProcessors($document);
        $lines = $this->executeProcessor($document);

        return $this->executePostProcessors($lines);
    }

    private function executePostProcessors(Text $text): Text
    {
        foreach ($this->postProcessors as $postProcessor) {
            $this->log(sprintf('Executing Post Processor "%s"', get_class($postProcessor)), ['order' => $postProcessor->order(), 'input' => $text->content()]);
            $text = $postProcessor->execute($text);
        }

        return $text;
    }

    private function executePreProcessors(Document $document): void
    {
        foreach ($this->preProcessors as $preProcessor) {
            $this->log(sprintf('Executing Pre Processor "%s"', get_class($preProcessor)), ['order' => $preProcessor->order(), 'input' => $document->path()]);
            $preProcessor->execute($document);
        }
    }

    private function executeProcessor(Document $document): Text
    {
        $processor = $this->getProcessor($document);

        $this->log(sprintf('Executing Processor "%s"', get_class($processor)));

        return $processor->execute($document);
    }

    private function getProcessor(Document $document): ProcessorInterface
    {
        $scores = [];
        foreach ($this->processors as $processor) {
            $score = $processor->score($document);
            $scores[] = ['score' => $score, 'processor' => $processor];

            $this->log(sprintf('Scored Processor "%s" with "%s"', get_class($processor), number_format($score, 0)));
        }

        usort($scores, function (array $processor1, array $processor2) {
            return $processor2['score'] <=> $processor1['score'];
        });

        return reset($scores)['processor'];
    }

    /** @param string[] $context */
    private function log(string $message, array $context = []): void
    {
        if (!$this->logger) {
            return;
        }
        $this->logger->info($message, $context);
    }
}
