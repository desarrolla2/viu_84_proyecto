<?php


/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Functional\Infrastructure\Reader\Service\ChatGPT;

use App\Domain\Reader\Entity\Person;
use App\Domain\Reader\Entity\ResidentialLeaseAgreementInterface;
use App\Domain\Reader\Service\ReaderEngine;
use App\Domain\Reader\ValueObject\Text;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ResidentialLeaseAgreementProcessorTest extends KernelTestCase
{
    public function dataProviderForTestProcessor(): array
    {
        return [
            '001.md' => [
                'source' => '001.md',
                'landLords' => ['numbers' => ['98765432B']],
                'tenants' => ['numbers' => ['82657077E']],
                'date' => '01/01/2023',
                'monthly_rent' => 1000,
            ],
            '002.md' => [
                'source' => '002.md',
                'landLords' => ['numbers' => ['98765432B']],
                'tenants' => ['numbers' => ['54321098C']],
                'date' => '02/02/2023',
                'monthly_rent' => 1000,
            ],
            '003.md' => [
                'source' => '003.md',
                'landLords' => ['numbers' => ['56382214Z']],
                'tenants' => ['numbers' => ['Z2772495B']],
                'date' => '03/03/2023',
                'monthly_rent' => 1000,
            ],
        ];
    }

    /** @dataProvider dataProviderForTestProcessor */
    public function testProcessor(string $source, array $landLords, array $tenants, string $date, float $rentalRent): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $path = sprintf('%s/tests/data/source/001/%s', $container->getParameter('kernel.project_dir'), $source);
        $text = new Text(file_get_contents($path));

        $engine = $container->get(ReaderEngine::class);

        /** @var ResidentialLeaseAgreementInterface $agreement */
        $agreement = $engine->execute($text);

        $this->assertInstanceOf(ResidentialLeaseAgreementInterface::class, $agreement);
        $this->assertPersons($agreement->landLords(), $landLords['numbers']);
        $this->assertPersons($agreement->tenants(), $tenants['numbers']);
        $this->assertEquals($date, $agreement->date()?->format('d/m/Y'));

        $this->assertEquals($rentalRent, $agreement->monthlyRent());
    }

    private function assertPersons(array $persons, array $expectedNumbers): void
    {
        $numbers = [];
        /** @var Person $person */
        foreach ($persons as $person) {
            $numbers[] = $person->number();
        }
        $this->assertSameSize($expectedNumbers, $numbers);
        foreach ($expectedNumbers as $expectedNumber) {
            $this->assertContains($expectedNumber, $numbers);
        }
    }
}
