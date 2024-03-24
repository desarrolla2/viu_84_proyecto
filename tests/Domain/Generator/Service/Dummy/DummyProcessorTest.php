<?php

namespace App\Tests\Domain\Generator\Service\Dummy;

use App\Domain\Generator\Entity\Document;
use App\Domain\Generator\Service\Dummy\DummyProcessor;
use App\Domain\Generator\ValueObject\Text;
use PHPUnit\Framework\TestCase;

class DummyProcessorTest extends TestCase
{
    public function testProcessor()
    {
        $engine = new DummyProcessor();
        $document = new Document('/var/www/tests/data/001/output/001.pdf');
        $text = $engine->execute($document);

        $this->assertInstanceOf(Text::class, $text);
    }
}
