<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Domain\Generator\Service\Common;

use App\Domain\Generator\Service\PostProcessorInterface;
use App\Domain\Generator\ValueObject\Text;

class WordLimitPostProcessor implements PostProcessorInterface
{
    private const  WORD_LIMIT = 1800;

    public static function order(): int
    {
        return 0;
    }

    public function execute(Text $text): Text
    {
        $words = explode(' ', $text->content());
        if (self::WORD_LIMIT < count($words)) {
            return new Text(implode(' ', array_slice($words, 0, self::WORD_LIMIT)));
        }

        return $text;
    }
}
