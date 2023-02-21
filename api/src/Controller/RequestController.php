<?php

namespace App\Controller;

use App\Entity\Availaibility;
use App\Enums\PropertyEnum;
use App\Enums\RequestEnum;
use App\Helpers\DateFormatterHelper;
use App\Repository\AvailaibilityRepository;
use App\Repository\RequestRepository;
use App\Service\EmailService;
use App\Service\LocationService;
use App\Service\SlotService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class RequestController extends AbstractController
{
    public function __construct(
        private readonly RequestRepository $requestRepository,
        private readonly AvailaibilityRepository $availaibilityRepository,
        private readonly SerializerInterface $serializer,
        private readonly LocationService $locationService,
        private readonly SlotService $slotService,
        private readonly EntityManagerInterface $manager,
        private readonly EmailService $emailService,
    )
    {
    }

    #[Route('/requests/by_owner/{ownerId}', name: 'get_requests_by_owner', methods: ['GET'])]
    #[IsGranted('ROLE_HOMEOWNER')]
    public function getRequestsByOwner($ownerId): JsonResponse
    {
        $requests = $this->requestRepository->findRequestsByOwnerId((int)$ownerId);

        if(!$requests) {
            return new JsonResponse([
                'message' => 'Vous n\'avez aucune demande pour le moment',
                'status' => 404
            ]);
        }

        return new JsonResponse(
            $this->serializer->serialize($requests, 'json'),
            200,
            [],
            true
        );
    }


    #[Route('/requests/by_lodger/{lodgerId}', name: 'get_requests_by_lodger', methods: ['GET'])]
    #[IsGranted('ROLE_TENANT')]
    public function getRequestsByLodger($lodgerId): JsonResponse
    {
        $requests = $this->requestRepository->findRequestsByLodgerId((int)$lodgerId);

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


    #[Route('/requests/{id}/slots/proposals', name: 'post_requests_slots',methods: ['POST'])]
    #[IsGranted('ROLE_HOMEOWNER')]
    public function postRequestSlotsProposals($id ,Request $request): JsonResponse
    {
        $propertyRequest = $this->requestRepository->find((int) $id);
        $slots = json_decode($request->getContent(), true);


        if(!$slots) {
            throw new \Exception('Aucune disponibilité n\'a été choisie');
        }

        if ($this->slotService->isSlotConform($propertyRequest, $slots)) {

            $limit = $this->slotService->countSlotToAdd($propertyRequest, $slots);

            foreach ($slots as $key => $slot) {

                $index = $key + 1;
                if ($index > $limit) break;

                $this->slotService->createSlot($slot, $propertyRequest);
            }

            $this->locationService->lockPropertyVisits($propertyRequest);
            $this->manager->flush();


            $this->emailService->send(
                new Address('mailer@easyhome.com', 'Easyhome Mail Bot'),
                $propertyRequest->getLodger()->getEmail(),
                'Easyhome : Proposition de visite',
                'viewings/proposal.html.twig', [
                    'property' => $propertyRequest->getProperty(),
                    'availaibilities' => $propertyRequest->getAvailaibilities()->toArray()
                ]
            );

            return new JsonResponse(
                $this->serializer->serialize($propertyRequest, 'json'),
                201,
                [],
                true
            );
        }

        return new JsonResponse([
            'message' => 'Vous avez atteint le nombre maximal de créneaux.',
            'status' => 406
        ]);
    }

    #[Route('/requests/{requestId}/slot/{slotId}', name: 'post_requests_slot', methods: ['POST'])]
    #[IsGranted('ROLE_TENANT')]
    public function postSlotAcceptedByTenant($requestId, $slotId): JsonResponse
    {
        $request = $this->requestRepository->find((int) $requestId);
        $property = $request->getProperty();
        $availaibility = $this->availaibilityRepository->find((int) $slotId);

        if (!$request || !$availaibility) {
            throw new \Exception('Une erreur est survenue', 404);
        }

        $viewing = $this->locationService->createViewing($availaibility);

        $request->setState(RequestEnum::Assignment->value);
        $property->setState(RequestEnum::Assignment->value);

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

    #[Route('/requests/{id}/slots', name: 'get_requests_slots', methods: ['GET'])]
    public function getAcceptedRequestSlots($id): JsonResponse
    {
        $request = $this->requestRepository->find((int) $id);

        if (!$request) {
            throw new \Exception('Votre demande est inexistante', 404);
        }

        if (RequestEnum::Accepted->value === $request->getState()) {

            $availaibilities = $request->getAvailaibilities()->toArray();
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
}