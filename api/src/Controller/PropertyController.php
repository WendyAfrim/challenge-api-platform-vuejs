<?php

namespace App\Controller;

use App\Repository\PropertyRepository;;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PropertyController extends AbstractController
{
    public function __construct(
        private readonly PropertyRepository $propertyRepository,
        private readonly Security $security,
    )
    {
    }

    public function __invoke(Request $request)
    {
        $user = $this->security->getUser();
        $page = (int) $request->query->get('page', 1);

        $properties = $this->propertyRepository->findPropertiesByTenantSalary($user, $page);

        if(!$properties) {
            return new JsonResponse([
                'message' => 'Aucun résultat pour l\'instant Mais ne ratez pas nos nouveautés !',
                'status' => 404,
            ]);
        }
        if($user->getRoles() ==! 'ROLE_TENANT') {
        return new JsonResponse([
            'message' => 'désolé vous n\'êtes pas \\n locataire !',
            'status' => 401
        ]);
    }

        return $properties;
//        JsonResponse(
//            $this->serializer->serialize($properties, 'json'),
//            200,
//            [],
//            true
//        );

    }

}