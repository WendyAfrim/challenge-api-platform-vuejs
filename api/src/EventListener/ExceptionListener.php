<?php

namespace App\EventListener;

use App\Entity\User;
use App\Exception\EmailNotVerifiedException;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Authentication Failure Listener.
 *
 * This listener add data to payload.
 */
class ExceptionListener
{
    #[AsEventListener]
    public function __invoke(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof EmailNotVerifiedException) {
            $event->setResponse(new JsonResponse([
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'error_type' => 'not_verified_email',
            ], 401));
            return;
        }
    }
}