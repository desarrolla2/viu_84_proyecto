<?php

namespace App\Tests\Domain\Generator\Service\Dummy;

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
