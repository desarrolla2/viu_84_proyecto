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
use App\Domain\Generator\Service\Dummy\DummyProcessor;
use App\Domain\Generator\ValueObject\Text;
use PHPUnit\Framework\TestCase;

class DummyProcessorTest extends TestCase
{
    public function testProcessor()
    {
        $engine = new DummyProcessor();
        $document = new Document('/var/www/tests/data/output/001/001.pdf');
        $text = $engine->execute($document);

        $this->assertInstanceOf(Text::class, $text);
    }
}
