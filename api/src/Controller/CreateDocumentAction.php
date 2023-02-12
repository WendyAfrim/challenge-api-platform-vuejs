<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\MediaObject;
use App\Enums\DocumentStatusEnum;
use App\Repository\DocumentRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
class CreateDocumentAction extends AbstractController
{
    public function __invoke(Request $request, UserRepository $userRepository, DocumentRepository $documentRepository): Document
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }
        if ($request->get('id')) {
            $mediaObject = $documentRepository->find($request->get('id'));
        } else {
            $mediaObject = new Document();
        }
        $mediaObject->setFile($uploadedFile);
        $mediaObject->setName($request->get('name'));
        $mediaObject->setType($request->get('type'));
        if ($request->get('is_valid')) {
            $mediaObject->setIsValid($request->get('is_valid'));
        }
        $user = $userRepository->find($request->get('user_id'));
        $mediaObject->setUserDocument($user);

        return $mediaObject;
    }
}