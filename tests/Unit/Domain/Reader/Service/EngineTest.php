<?php


/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Domain\Reader\Service;

use App\Domain\Reader\Entity\DummyAgreementInterface;
use App\Domain\Reader\Service\Dummy\DummyProcessor;
use App\Domain\Reader\Service\ReaderEngine;
use App\Domain\Reader\ValueObject\Text;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class EngineTest extends TestCase
{
    public function dataProviderForTestEngine(): array
    {
        return [
            ['01.doc', 'hash'],
            ['02.doc', 'hash'],
            ['03.doc', 'hash'],
            ['04.doc', 'hash'],
            ['05.doc', 'hash'],
            ['06.doc', 'hash'],
            ['07.doc', 'hash'],
            ['08.doc', 'hash'],
        ];
    }

    /** @dataProvider dataProviderForTestEngine */
    public function testEngine(string $fileName, string $hash): void
    {
        $engine = new ReaderEngine(new NullLogger());
        $engine->addProcessors([new DummyProcessor()]);

        $document = new Text($fileName);
        $text = $engine->execute($document);

        $this->assertInstanceOf(DummyAgreementInterface::class, $text);
    }
}
