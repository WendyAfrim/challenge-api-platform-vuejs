<?php

namespace App\Controller;

use App\Repository\ViewingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ViewingController extends AbstractController
{
    public function __construct(
        private readonly ViewingRepository $viewingRepository,
        private readonly SerializerInterface $serializer
    )
    {
    }

    #[Route('/viewings/by_owner/{ownerId}', name: 'get_viewings_by_owner')]
    public function getVisitsByOwner(int $ownerId): JsonResponse
    {
        $viewings = $this->viewingRepository->findViewingsByOwner($ownerId);

        if(!$viewings) {
            return new JsonResponse([
                'message' => 'Aucune visite n\'est programmée pour le moment !',
                'status' => 404
            ]);
        }

        return new JsonResponse(
            $this->serializer->serialize($viewings, 'json'),
            200,
            [],
            true
        );

    }

}