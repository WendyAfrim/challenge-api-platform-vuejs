<?php declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Used to empty documentation pages in prod environment
 */
final class ApiDocumentationSubscriber implements EventSubscriberInterface
{
    private const PROTECTED_PATHS = [
        '^/$',
        '^/docs',
        '^/contexts',
    ];

    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 249],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($this->parameterBag->get('app.env') !== 'prod') {
            return;
        }

        $path = $event->getRequest()->getPathInfo();
        $path = rtrim($path, '/');

        foreach (self::PROTECTED_PATHS as $protectedPath) {
            // Build regex php style
            $protectedPath = str_replace('/', '\/', $protectedPath);
            $protectedPath = '/' . $protectedPath . '/';

            preg_match($protectedPath, $path, $match);

            if ($match) {
                throw new NotFoundHttpException();
            }
        }
    }
}