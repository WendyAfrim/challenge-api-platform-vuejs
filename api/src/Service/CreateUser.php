<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUser
{
    private EmailVerifier $emailVerifier;
    private UserPasswordHasherInterface $userPasswordHasher;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private UserRepository $userRepository;

    public function __construct(EmailVerifier $emailVerifier, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserRepository $userRepository)
    {
        $this->emailVerifier = $emailVerifier;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    public function create(Array $data, Array $roles): Response
    {
        $user = new User();
        if (isset($data['email'])) $user->setEmail($data['email']);
        if (isset($data['plainPassword'])) $user->setPassword($this->userPasswordHasher->hashPassword($user, $data['plainPassword']));
        $user->setRoles($roles);
        $errors = $this->validator->validate($user, null, ['registration']);
        if (count($errors) > 0) {
            $errorsOutput = '';
            foreach ($errors as $error) {
                $errorsOutput . $error->getPropertyPath() . ': ' . $error->getMessage() . '\\n';
            }
            throw new BadRequestHttpException($errorsOutput);
        }
        $this->entityManager->persist($user);
        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new BadRequestHttpException('Un compte avec cet email existe déjà');
        }

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('mailer@easyhome.com', 'Easyhome Mail Bot'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
        return new JsonResponse('Un mail vous a été envoyé. Cliquez sur le lien pour compléter votre inscription.', 201);
    }
}