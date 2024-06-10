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
use App\Domain\Reader\Entity\VehicleSaleAndPurchaseAgreement;
use App\Domain\Reader\Service\ReaderEngine;
use App\Domain\Reader\ValueObject\Text;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VehicleSaleAndPurchaseAgreementProcessorTest extends KernelTestCase
{
    public function dataProviderForTestProcessor(): array
    {
        return [
            '001.md' => [
                'source' => '001.md',
                'sellers' => ['numbers' => ['12345678A']],
                'buyers' => ['numbers' => ['87654321B']],
                'date' => '15/05/2024',
                'price' => 5000,
            ],
            '002.md' => [
                'source' => '002.md',
                'sellers' => ['numbers' => ['98765432X']],
                'buyers' => ['numbers' => ['54321678Y']],
                'date' => '15/05/2024',
                'price' => 6500,
            ],
            '003.md' => [
                'source' => '003.md',
                'sellers' => ['numbers' => ['12345678A']],
                'buyers' => ['numbers' => ['98765432B']],
                'date' => '15/05/2024',
                'price' => 2500,
            ],
        ];
    }

    /** @dataProvider dataProviderForTestProcessor */
    public function testProcessor(string $source, array $sellers, array $buyers, string $date, float $price): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $path = sprintf('%s/tests/data/source/002/%s', $container->getParameter('kernel.project_dir'), $source);
        $text = new Text(file_get_contents($path));

        $engine = $container->get(ReaderEngine::class);

        /** @var VehicleSaleAndPurchaseAgreement $agreement */
        $agreement = $engine->execute($text);

        $this->assertInstanceOf(VehicleSaleAndPurchaseAgreement::class, $agreement);
        $this->assertPersons($agreement->sellers(), $sellers['numbers']);
        $this->assertPersons($agreement->buyers(), $buyers['numbers']);
        $this->assertEquals($date, $agreement->date()?->format('d/m/Y'));

        $this->assertEquals($price, $agreement->price());
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
