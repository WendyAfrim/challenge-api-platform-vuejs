<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class MeController extends AbstractController
{
    public function __construct(private readonly Security $security)
    {
    }

    public function __invoke()
    {
        $user = $this->security->getUser();
        if(!$user){
            return new JsonResponse([
                'error' => 'Authentication required'
            ], 401);
        }
        return $this->json($user);
    }

}