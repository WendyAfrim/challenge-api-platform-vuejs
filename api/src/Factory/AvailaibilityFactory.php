<?php

namespace App\Factory;

use App\Entity\Availaibility;

class AvailaibilityFactory
{
    public function createAvailaibilities(int $number = 1): array
    {
        $availabilities = [];
        for($i = 0; $i < $number; $i++) {
            $availabilities[] = (new Availaibility())
                                    ->setSlot(new \DateTime());
        }

        return $availabilities;
    }
}