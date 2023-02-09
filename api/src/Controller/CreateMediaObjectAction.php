<?php

namespace App\Controller;



use App\Entity\MediaObject;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


#[AsController]
class CreateMediaObjectAction extends AbstractController
{
    public function __invoke(Request $request, PropertyRepository $propertyRepository): MediaObject
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }
        $mediaObject = new MediaObject();
        $mediaObject->file = $uploadedFile;
        $property = $propertyRepository->find($request->get('property_id'));
        $mediaObject->setProperty($property);
        return $mediaObject;
    }
}