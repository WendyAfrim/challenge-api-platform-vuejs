<?php

namespace App\Checker;

use App\Entity\User;
use App\Repository\RequestRepository;

class RequestChecker
{
    public function __construct(
        private readonly RequestRepository $requestRepository
    )
    {
    }

    public function isOwnerProperty(User $user, int $requestId): bool
    {
        $properties = $user->getProperties();

        foreach ($properties as $property) {

            $requests = $property->getRequests();

            foreach ($requests as $request) {
                if ($request->getId() === $requestId) { return true ;}
            }
        }

        return false;
    }

    public function checkIfTenantAttachToRequest(int $requestId, int $lodgerId): bool
    {
        $request = $this->requestRepository->find($requestId);

        if ($request->getLodger()->getId() === $lodgerId) { return true; }

        return false;
    }
}