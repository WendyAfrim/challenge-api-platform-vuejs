<?php

namespace App\Factory;

use App\Entity\Availaibility;

class AvailabilityFactory
{
    public function createSlots(int $slotNumber = 1): array
    {
        $slots = [];
        for($i = 0; $i < $slotNumber; $i++) {
            $randomSlot = mt_rand(1, 2147385600);
            $slots[] = $randomSlot;
        }

        return $slots;
    }

    public function createAvailability(bool $requestStateIsViewing): Availaibility
    {
        $requestFactory = new RequestFactory();

        return (new Availaibility())
                ->setSlot(new \DateTime())
                ->setRequest($requestFactory->createRequest(3, $requestStateIsViewing));
    }
}