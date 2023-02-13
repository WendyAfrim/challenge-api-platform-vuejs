<?php

namespace App\Service;

use App\Entity\Request;
use App\Entity\Viewing;
use App\Enums\RequestEnum;
use App\Repository\AvailaibilityRepository;
use App\Repository\RequestRepository;

class LocationService
{

    public function __construct(
        private readonly AvailaibilityRepository $availaibilityRepository,
        private readonly RequestRepository $requestRepository
    )
    {
    }

    public function isSlotConform(string $propertyId, string $lodgerId): bool
    {
        $slots = $this->availaibilityRepository->findAvailaibilityByPropertyAndLodger($propertyId, $lodgerId);

        if (count($slots) >= 3) {
            return false;
        }

        return true;
    }

    public function lockPropertyVisits(int $propertyId, int $lodgerId): void
    {
        $requests = $this->requestRepository->findRequestsByPropertyId($propertyId);

        array_walk($requests, function(Request $request) use ($lodgerId) {

            $requestLodger = $request->getLodger()->getId();
            $request->setState($lodgerId === $requestLodger ? RequestEnum::Accepted->value : RequestEnum::Refused->value);
        });
    }

    public function createViewing(int $slotId): Viewing
    {
        $slot = $this->availaibilityRepository->find($slotId);

        return ((new Viewing())
                    ->setAvailaibility($slot)
                    ->setLodger($slot->getLodger()));

    }
}