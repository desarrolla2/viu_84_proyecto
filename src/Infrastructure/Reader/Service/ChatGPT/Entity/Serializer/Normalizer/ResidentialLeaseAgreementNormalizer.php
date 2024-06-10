<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Infrastructure\Reader\Service\ChatGPT\Entity\Serializer\Normalizer;

use App\Domain\Reader\Entity\Address;
use App\Domain\Reader\Entity\Person;
use App\Domain\Reader\Entity\Property;
use App\Domain\Reader\Entity\ResidentialLeaseAgreement;
use DateTime;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AutoconfigureTag('serializer.normalizer')]
readonly class ResidentialLeaseAgreementNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function __construct(private PropertyNormalizer $propertyNormalizer, private PersonNormalizer $personNormalizer)
    {
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): ResidentialLeaseAgreement
    {
        $date = $data['date'] ? DateTime::createFromFormat('Y-m-d', $data['date']) : '';
        $property = $data['property'] ? $this->propertyNormalizer->denormalize($data['property'], Property::class) : new Property(new Address('', '', '', ''));
        $landLords = $data['landlords'] ? array_map(function (array $item): Person {
            return $this->personNormalizer->denormalize($item, Person::class);
        }, $data['landlords']) : [];
        $tenants = $data['tenants'] ? array_map(function (array $item): Person {
            return $this->personNormalizer->denormalize($item, Person::class);
        }, $data['tenants']) : [];
        $monthlyRentalPrice = (float) $data['monthly_rent'] ?? 0.0;

        return new ResidentialLeaseAgreement($date, $property, $landLords, $tenants, $monthlyRentalPrice);
    }

    /** @param ResidentialLeaseAgreement $object */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        return [
            'date' => $object->date()->format('Y-m-d'),
            'property' => $this->propertyNormalizer->normalize($object->property()),
            'land_lords' => array_map(function (Person $person): array {
                return $this->personNormalizer->normalize($person);
            }, $object->landLords()),
            'tenants' => array_map(function (Person $person): array {
                return $this->personNormalizer->normalize($person);
            }, $object->landLords()),
            'monthly_rent' => $object->monthlyRent(),
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ResidentialLeaseAgreement;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [ResidentialLeaseAgreement::class => true, ];
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === ResidentialLeaseAgreement::class;
    }
}
