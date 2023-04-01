<?php

namespace App\Controller;

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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\ExpiredSignatureException;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class  RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private ValidatorInterface $validator;
    private UserRepository $userRepository;

    public function __construct(EmailVerifier $emailVerifier, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserRepository $userRepository)
    {
        $this->emailVerifier = $emailVerifier;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
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
            throw new BadRequestHttpException('Vous êtes déjà connecté');
        }
        $data = $request->toArray();
        if (!isset($data['email'])) {
            throw new BadRequestHttpException('Email manquant');
        }
        $errors = $this->validator->validate($data['email'], new Email());
        if (count($errors) > 0) {
            $errorsOutput = "";
            foreach ($errors as $error) {
                $errorsOutput.$error->getPropertyPath()." : ".$error->getMessage() ."\n";
            }
            throw new BadRequestHttpException( $errorsOutput);
        }
        $user =  $this->userRepository->findOneBy(['email' => $data['email']]);
        if (!$user) {
            throw new BadRequestHttpException('Aucun utilisateur avec cet email');
        }
        if ($user->isVerified()) {
            throw new BadRequestHttpException('Votre email est déjà validé');
        }
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
        (new TemplatedEmail())
            ->from(new Address('mailer@easyhome.com', 'Easyhome Mail Bot'))
            ->to($user->getEmail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('registration/confirmation_email.html.twig')
        );
        throw new BadRequestHttpException('Un mail vous a été envoyé. Cliquez sur le lien pour compléter votre inscription.');
    }
}
