<?php

namespace App\Domain\Reader\Service\Dummy;

use App\Domain\Reader\Entity\AgreementInterface;
use App\Domain\Reader\Entity\DummyAgreement;
use App\Domain\Reader\Service\ProcessorInterface;
use App\Domain\Reader\ValueObject\Text;

class DummyProcessor implements ProcessorInterface
{
    public function execute(Text $text): AgreementInterface
    {
        return new DummyAgreement();
    }

    public function score(Text $text): int
    {
        return 1;
    }
}
