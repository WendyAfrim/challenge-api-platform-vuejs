<?php

namespace App\Service;

use App\Entity\Availaibility;
use App\Entity\Request;
use App\Entity\Viewing;
use App\Enums\AvailaibilityEnum;
use App\Enums\PropertyEnum;
use App\Enums\RequestEnum;
use Doctrine\Common\Collections\Collection;

class LocationService
{

    public function lockPropertyVisits(Request $request): void
    {
        $choosenLodgerId = $request->getLodger()->getId();
        $property = $request->getProperty();
        $allPropertyRequests = $property->getRequests()->toArray();

        array_walk($allPropertyRequests, function(Request $request) use ($choosenLodgerId) {

            $requestLodger = $request->getLodger()->getId();
            $request->setState($choosenLodgerId === $requestLodger ? RequestEnum::Accepted->value : RequestEnum::Refused->value);
        });
        
        $property->setState(PropertyEnum::Locked->value);
    }

    private function cancelOtherSlots(Request $request, Availaibility $availaibilityChoosenByTenant): void
    {
        $availaibilities = $request->getAvailaibilities()->toArray();

        array_walk($availaibilities, function(Availaibility $availaibility) use ($availaibilityChoosenByTenant){

            if ($availaibility === $availaibilityChoosenByTenant) {
                $availaibility->setState(AvailaibilityEnum::Accepted->value);
                return;
            }
            $availaibility->setState(AvailaibilityEnum::Refused->value);
        });
    }

    public function createViewing(Availaibility $choosenAvailaibility): Viewing
    {
        $request = $choosenAvailaibility->getRequest();

       if (RequestEnum::Viewing->value == $request->getState()) {
            throw new \Exception('Une visite est déjà programmé sur cette demande', 406);
       }

       $this->cancelOtherSlots($request, $choosenAvailaibility);

        return ((new Viewing())
                    ->setAvailaibility($choosenAvailaibility)
                    ->setLodger($choosenAvailaibility->getRequest()->getLodger()));

    }
}