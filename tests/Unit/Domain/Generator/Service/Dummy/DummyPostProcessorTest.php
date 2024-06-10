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

use App\Domain\Generator\Service\Dummy\DummyPostProcessor;
use App\Domain\Generator\ValueObject\Text;
use PHPUnit\Framework\TestCase;

class DummyPostProcessorTest extends TestCase
{
    public function testPostProcessor()
    {
        $preProcessor = new DummyPostProcessor();
        $content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit';

        $text = $preProcessor->execute(new Text($content));

        $this->assertInstanceOf(Text::class, $text);
        $this->assertEquals($content, $text->content());
    }
}
