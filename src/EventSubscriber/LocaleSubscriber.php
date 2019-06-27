<?php
namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LocaleSubscriber implements EventSubscriberInterface
{
private $defaultLocale;
    private $tokenStorage;

public function __construct(TokenStorageInterface $tokenStorage,$defaultLocale = 'ua' )
{
    $this->tokenStorage = $tokenStorage;
$this->defaultLocale = $defaultLocale;
}

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->hasPreviousSession()) {
        return;
        }

        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } else {
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }

    }

    public static function getSubscribedEvents()
    {
        return array(
        // должен быть зарегистрирован после слушателя локали по умолчанию
        KernelEvents::REQUEST => array(array('onKernelRequest', 15)),
        );
    }

}