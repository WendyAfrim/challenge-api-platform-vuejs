<?php

namespace App\Controller;



use App\Entity\User;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Service\CreateUser;

#[AsController]
class CreateTenantAction extends AbstractController
{
    public function __invoke(Request $request, PropertyRepository $propertyRepository, CreateUser $createUser): Response
    {
        $data = $request->toArray();
        return $createUser->create($data, [User::ROLE_TENANT]);
    }
}