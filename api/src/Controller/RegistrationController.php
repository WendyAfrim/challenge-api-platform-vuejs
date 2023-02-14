<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\ExpiredSignatureException;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
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

    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        $data = $request->toArray();

        $user = new User();
        if (isset($data['email'])) $user->setEmail($data['email']);
        if (isset($data['roles'])) $user->setRoles($data['roles']);
        if (isset($data['plainPassword'])) $user->setPassword($this->userPasswordHasher->hashPassword($user, $data['plainPassword']));

        $errors = $this->validator->validate($user, null, ['registration']);
        if (count($errors) > 0) {
            $errorsOutput = [];
            foreach ($errors as $error) {
                $errorsOutput[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorsOutput], 400);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('mailer@easyhome.com', 'Easyhome Mail Bot'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );

        return new JsonResponse(['message' => 'Un mail vous a été envoyé. Cliquez sur le lien pour compléter votre inscription.'], 201);
    }

    #[Route('/test', name: 'app_super_test')]
    public function test(): Response
    {
        return new Response(
            '<html><body>Lucky number:</body></html>'
        );
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): JsonResponse|RedirectResponse|Response
    {
        $id = $request->get('id');
            if (null === $id) {
            return $this->redirect($_ENV['BASE_URL'].'/login?'.http_build_query(['status' => 'error']));
        }

        $user = $this->userRepository->find($id);

        if (null === $user) {
            return $this->redirect($_ENV['BASE_URL'].'/login?'.http_build_query(['status' => 'error']));
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (ExpiredSignatureException $exception) {
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('mailer@easyhome.com', 'Easyhome Mail Bot'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            return $this->redirect($_ENV['BASE_URL'].'/login?'.http_build_query(['status' => 'expired']));
        } catch (VerifyEmailExceptionInterface $exception) {
            return $this->redirect($_ENV['BASE_URL'].'/login?'.http_build_query(['status' => 'error']));
        }
        return $this->redirect($_ENV['BASE_URL'].'/login?'.http_build_query(['status' => 'validated']));
    }

    #[Route('/verify/request-new-link', name: 'app_request_verify_email')]
    public function requestVerifyUserEmail(Request $request): JsonResponse {

        if ($this->getUser()) {
            return new JsonResponse(['message' => 'Vous êtes déjà connecté'], 400);
        }
        $data = $request->toArray();
        if (!isset($data['email'])) {
            return new JsonResponse(['message' => 'Email manquant'], 400);
        }
        $errors = $this->validator->validate($data['email'], new Email());
        if (count($errors) > 0) {
            $errorsOutput = [];
            foreach ($errors as $error) {
                $errorsOutput[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorsOutput], 400);
        }
        $user =  $this->userRepository->findOneBy(['email' => $data['email']]);
        if (!$user) {
            return new JsonResponse(['message' => 'Aucun utilisateur avec cet email'], 400);
        }
        if ($user->isVerified()) {
            return new JsonResponse(['message' => 'Votre email est déjà validé'], 400);
        }
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
        (new TemplatedEmail())
            ->from(new Address('mailer@easyhome.com', 'Easyhome Mail Bot'))
            ->to($user->getEmail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('registration/confirmation_email.html.twig')
        );
        return new JsonResponse(['message' => 'Un mail vous a été envoyé. Cliquez sur le lien pour compléter votre inscription.'], 201);
    }
}
