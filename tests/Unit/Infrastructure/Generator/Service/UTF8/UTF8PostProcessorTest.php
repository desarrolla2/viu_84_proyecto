<?php

namespace App\Tests\Unit\Infrastructure\Generator\Service\UTF8;

use App\Domain\Generator\ValueObject\Text;
use App\Infrastructure\Generator\Service\UTF8\UTF8PostProcessor;
use PHPUnit\Framework\TestCase;

class UTF8PostProcessorTest extends TestCase
{
    public function dataProviderForTestPostProcessor(): array
    {
        return [
            ['????????', 'čęėįšųūž'],
            ['Fédération Camerounaise?de?Football', 'FÃÂ©dération Camerounaise—de—Football'],
        ];
    }

    /** @dataProvider dataProviderForTestPostProcessor */
    public function testPostProcessor(string $expected, string $actual)
    {
        $postProcessor = new UTF8PostProcessor();
        $text = $postProcessor->execute(new Text($actual));

        $this->assertInstanceOf(Text::class, $text);
        $this->assertEquals($expected, $text->content());
    }

}
