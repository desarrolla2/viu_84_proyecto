<?php


/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Domain\Generator\Service;

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
            ['/var/www/tests/data/output/001/001.pdf', ''],
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
