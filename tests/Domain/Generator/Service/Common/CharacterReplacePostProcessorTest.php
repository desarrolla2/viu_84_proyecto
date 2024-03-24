<?php

namespace App\Tests\Domain\Generator\Service\Common;

use App\Domain\Generator\Service\Common\CharacterReplacePostProcessor;
use App\Domain\Generator\ValueObject\Text;
use PHPUnit\Framework\TestCase;

class CharacterReplacePostProcessorTest extends TestCase
{
    public function testPostProcessor()
    {
        $postProcessor = new CharacterReplacePostProcessor();
        $text = $postProcessor->execute(new Text("\f"));

        $this->assertInstanceOf(Text::class, $text);
        $this->assertEquals("\n", $text->content());
    }
}
