<?php

namespace App\Controller;

use App\Entity\Availaibility;
use App\Enums\RequestEnum;
use App\Repository\RequestRepository;
use App\Service\EmailService;
use App\Service\LocationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RequestController extends AbstractController
{
    public function __construct(
        private readonly RequestRepository $requestRepository,
        private readonly SerializerInterface $serializer,
        private readonly LocationService $locationService,
        private readonly EntityManagerInterface $manager,
        private readonly EmailService $emailService
    )
    {
    }

    #[Route('/requests/by_lodger/{lodgerId}', name: 'get_requests_by_lodger', methods: ['GET'])]
    public function getRequestsByLodger(int $lodgerId): JsonResponse
    {
        $requests = $this->requestRepository->findRequestsByLodgerId($lodgerId);

        if(!$requests) {
            return new JsonResponse([
                'message' => 'Vous n\'avez aucune demande pour le moment',
                404
            ]);
        }

        return new JsonResponse(
            $this->serializer->serialize($requests, 'json'),
            200,
            [],
            true
        );
    }

    #[Route('/requests/{id}/slots', name: 'get_requests_slots', methods: ['GET'])]
    public function getAcceptedRequestSlots(int $id): JsonResponse
    {
        $request = $this->requestRepository->find($id);

        if (!$request) {
            throw new \Exception('Votre demande est inexistante', 404);
        }

        if (RequestEnum::Accepted->value === $request->getState()) {

            $lodger = $request->getLodger();
            $property = $request->getProperty();

            $availaibilities = $property->getAvailaibilities()->filter(function(Availaibility $availaibility) use ($lodger) {
                return $availaibility->getLodger()->getId() === $lodger->getId();
            });

            return new JsonResponse(
                $this->serializer->serialize($availaibilities, 'json'),
                200,
                [],
                true
            );
        }

        return new JsonResponse([
            'message' => 'Le demande n\'a pas encore été accepté par le propriétaire',
            'code' => 406
        ]);
    }


    #[Route('/requests/{requestId}/slot/{slotId}', name: 'post_requests_slot', methods: ['POST'])]
    public function postRequestSlot(int $requestId, int $slotId): JsonResponse
    {
        $request = $this->requestRepository->find($requestId);

        if (!$request) {
            throw new \Exception('Votre demande est inexistante', 404);
        }

        $request->setState(RequestEnum::Viewing->value);

        $viewing = $this->locationService->createViewing($slotId);

        $this->manager->persist($viewing);
        $this->manager->flush();

        $this->emailService->send(
            new Address('no-reply@easyhome.com', 'Easyhome'),
            $request->getProperty()->getOwner()->getEmail(),
            $request->getProperty()->getTitle().' : Acceptation de créneau',
            'viewings/acceptance.html.twig', [
                'property' => $request->getProperty(),
                'lodger' => $request->getLodger(),
                'availaibility' => $viewing->getAvailaibility()
            ]
        );

        return new JsonResponse(
            $this->serializer->serialize($viewing, 'json'),
            201,
            [],
            true
        );
    }
}