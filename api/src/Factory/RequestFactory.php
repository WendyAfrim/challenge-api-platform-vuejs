<?php

namespace App\Factory;

use App\Entity\Request;

class RequestFactory
{
    public function createRequest(int $availaibilityNumber): Request
    {
        $availabilityFactory = new AvailaibilityFactory();
        $availabilities = $availabilityFactory->createAvailaibilities($availaibilityNumber);

        $request = new Request();

        foreach ($availabilities as $availaibility) {
            $request->addAvailaibility($availaibility);
        }

        return $request;
    }
}