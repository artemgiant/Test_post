<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LastLoginSubscriber implements EventSubscriberInterface
{
    protected $entityManager;
    private $session;

    public function __construct(EntityManagerInterface $entityManager,SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();

        if (null !== $user->getLocale()) {
            $this->session->set('_locale', $user->getLocale());
        }

        $user->setLastLogin(new \DateTime());
        $this->entityManager->persist($user);
        $this->entityManager->flush();

    }

    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin'
        ];
    }
}
