<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PasswordChangeSubscriber implements EventSubscriberInterface
{
    private const ALLOWED_ROUTES = [
        'app_change_password',
        'app_logout',
    ];

    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 10],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $route = $request->attributes->get('_route');

        // Skip if route is allowed or is a public route
        if (in_array($route, self::ALLOWED_ROUTES) || str_starts_with($route, 'app_login')) {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return;
        }

        // Update last login timestamp
        if ($user->getLastLoginAt() === null) {
            $user->setLastLoginAt(new \DateTimeImmutable());
            $this->entityManager->flush();
        }

        // Force password change if required
        if ($user->isMustChangePassword()) {
            $response = new RedirectResponse(
                $this->urlGenerator->generate('app_change_password')
            );
            $event->setResponse($response);
        }
    }
}
