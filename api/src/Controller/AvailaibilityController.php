<?php

namespace App\Controller;

use App\Entity\Availaibility;
use App\Helpers\DateFormatterHelper;
use App\Repository\AvailaibilityRepository;
use App\Repository\PropertyRepository;
use App\Repository\UserRepository;
use App\Service\EmailService;
use App\Service\LocationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Address;

class AvailaibilityController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PropertyRepository $propertyRepository,
        private readonly AvailaibilityRepository $availaibilityRepository,
        private readonly EntityManagerInterface $manager,
        private readonly EmailService $emailService,
        private readonly LocationService $locationService
    )
    {
    }

    #[Route('/availaibilities/proposed/by_owner', name: 'post_owner_availaibilities')]
    public function postOwnerAvailaibilities(Request $request): JsonResponse
    {
        $availaibilities = json_decode($request->getContent(), true);

        if(!$availaibilities) {
            throw new \Exception('Aucune disponibilité n\'a été choisie');
        }

        if ($this->locationService->isSlotConform($availaibilities[0]['property'], $availaibilities[0]['lodger'])) {

            foreach ($availaibilities as $availaibility) {

                $slot = ((new Availaibility())
                    ->setSlot(DateFormatterHelper::stringToDatetime($availaibility['slot']))
                    ->setProperty($this->propertyRepository->find($availaibility['property']))
                    ->setLodger($this->userRepository->find($availaibility['lodger']))
                );

                $this->manager->persist($slot);
            }

            $this->manager->flush();

            return new JsonResponse([
                'message' => 'Vos disponibilités ont bien été enregistrées',
                'status' => 201
            ]);
        }

        return new JsonResponse([
            'message' => 'Des créneaux ont déjà été proposés pour ce bien.',
            'status' => 406
        ]);

    }

    #[Route('/send/property/{propertyId}/availaibility/{lodgerId}', name: 'send_email_availaibilities')]
    public function sendAvailaibilityToLodger(int $propertyId ,int $lodgerId): JsonResponse
    {
        $property = $this->propertyRepository->find($propertyId);
        $lodger = $this->userRepository->find($lodgerId);
        $availaibilities = $this->availaibilityRepository->findAvailaibilityByPropertyAndLodger($propertyId, $lodgerId);

        $this->emailService->send(
            new Address('mailer@easyhome.com', 'Easyhome Mail Bot'),
            $lodger->getEmail(),
            'Easyhome : Proposition de visite',
            'visits/proposal.html.twig', [
                'property' => $property,
                'availaibilities' => $availaibilities
            ]
        );

        return new JsonResponse([
            'message' => 'Vos disponibilités ont bien été enregistrées ! Un email a été envoyé au futur locataire',
            200
        ]);
    }
}
