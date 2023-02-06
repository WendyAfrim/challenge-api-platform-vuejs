<?php

namespace App\Controller;



use App\Entity\Document;
use App\Entity\MediaObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


#[AsController]
class CreateDocumentAction extends AbstractController
{
    public function __invoke(Request $request): Document
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }
        $mediaObject = new Document();
        $mediaObject->file = $uploadedFile;
        $mediaObject->setName($request->get('name'));
        $mediaObject->setType($request->get('type'));
        return $mediaObject;
    }
}