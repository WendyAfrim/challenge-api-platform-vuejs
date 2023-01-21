<?php

namespace App\Service;

use App\Repository\AvailaibilityRepository;

class LocationService
{

    public function __construct(
        private readonly AvailaibilityRepository $availaibilityRepository
    )
    {
    }

    public function isSlotConform(string $property, string $lodger): bool
    {
        $slots = $this->availaibilityRepository->findAvailaibilityByPropertyAndLodger($property, $lodger);

        if (count($slots) >= 3) {
            return false;
        }

        return true;
    }
}