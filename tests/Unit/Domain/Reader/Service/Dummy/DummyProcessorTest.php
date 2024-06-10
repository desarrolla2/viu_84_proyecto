<?php


/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Unit\Domain\Reader\Service\Dummy;

use App\Domain\Reader\Entity\DummyAgreementInterface;
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

        $this->assertInstanceOf(DummyAgreementInterface::class, $text);
    }

}
