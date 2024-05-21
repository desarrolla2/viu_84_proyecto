<?php

namespace App\Domain\Reader\Service;

use App\Domain\Reader\Entity\Agreement;
use App\Domain\Reader\ValueObject\Text;

interface ProcessorInterface
{
    public function execute(Text $text): Agreement;

    public function score(Text $text): int;
}
