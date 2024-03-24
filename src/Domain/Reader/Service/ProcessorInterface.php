<?php

namespace App\Domain\Reader\Service;

use App\Domain\Reader\Entity\AgreementInterface;
use App\Domain\Reader\ValueObject\Text;

interface ProcessorInterface
{
    public function execute(Text $text): AgreementInterface;

    public function score(Text $text): int;
}
