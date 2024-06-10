<?php


/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Domain\Generator\Service\Dummy;

use App\Domain\Generator\Entity\Document;
use App\Domain\Generator\Service\Dummy\DummyPreProcessor;
use PHPUnit\Framework\TestCase;

class DummyPreProcessorTest extends TestCase
{
    public function testPreProcessor()
    {
        $preProcessor = new DummyPreProcessor();
        $path = '/var/www/tests/data/output/001/001.pdf';
        $document = new Document($path);

        $preProcessor->execute($document);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertEquals($path, $document->path());
    }
}
