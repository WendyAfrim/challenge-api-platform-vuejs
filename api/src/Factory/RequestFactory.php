<?php

namespace App\Factory;

use App\Entity\Request;
use App\Enums\RequestEnum;

class RequestFactory
{
    public function createRequest(int $availaibilityNumber = 1, $isRequestStateViewing = true): Request
    {
        $availabilityFactory = new AvailaibilityFactory();
        $availabilities = $availabilityFactory->createAvailaibilities($availaibilityNumber);

        $request = new Request();

        foreach ($availabilities as $availaibility) {
            $request->addAvailaibility($availaibility);
        }

        $request->setState($isRequestStateViewing === true ? RequestEnum::Viewing->value : RequestEnum::Accepted->value);

        return $request;
    }
}