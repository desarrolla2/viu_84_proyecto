<?php

namespace App\Tests\Unit\Domain\Reader\Service\Dummy;

use App\Domain\Reader\Entity\DummyAgreement;
use App\Domain\Reader\Service\Dummy\DummyProcessor;
use App\Domain\Reader\Service\ReaderEngine;
use App\Domain\Reader\ValueObject\Text;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class DummyProcessorTest extends TestCase
{
    public function testProcessor()
    {
        $engine = new ReaderEngine(new NullLogger());
        $engine->addProcessors([new DummyProcessor()]);

        $document = new Text();
        $text = $engine->execute($document);

        $this->assertInstanceOf(DummyAgreement::class, $text);
    }

}
