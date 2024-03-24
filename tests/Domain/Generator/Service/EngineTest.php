<?php

namespace App\Tests\Domain\Generator\Service;

use App\Domain\Generator\Entity\Document;
use App\Domain\Generator\Service\Dummy\DummyPostProcessor;
use App\Domain\Generator\Service\Dummy\DummyPreProcessor;
use App\Domain\Generator\Service\Dummy\DummyProcessor;
use App\Domain\Generator\Service\GeneratorEngine;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class EngineTest extends TestCase
{
    public function dataProviderForTestEngine(): array
    {
        return [
            ['/var/www/tests/data/001/output/001.pdf', ''],
        ];
    }

    /** @dataProvider dataProviderForTestEngine */
    public function testEngine(string $fileName, string $content): void
    {
        $engine = new GeneratorEngine(new NullLogger());

        $engine->addPreProcessor(new DummyPreProcessor());
        $engine->addProcessor(new DummyProcessor());
        $engine->addPostProcessor(new DummyPostProcessor());

        $document = new Document($fileName);
        $text = $engine->execute($document);

        $this->assertEquals($content, $text->content());
    }
}
