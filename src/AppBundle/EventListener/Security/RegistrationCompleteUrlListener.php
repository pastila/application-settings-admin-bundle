<?php

namespace AppBundle\EventListener\Security;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegistrationCompleteUrlListener implements EventSubscriberInterface
{
  public function onInitialize (GetResponseUserEvent $event)
  {
    $request = $event->getRequest();

    if ($request->get('backUrl'))
    {
      $request->getSession()->set('registration_complete_url', $request->get('backUrl'));
    }
  }

  public function onSuccess (FormEvent $event)
  {
    $request = $event->getRequest();

    if ($request->getSession()->get('registration_complete_url'))
    {
      $response = new RedirectResponse($request->getSession()->get('registration_complete_url'));
      $event->setResponse($response);
      $request->getSession()->remove('registration_complete_url');
    }
  }

  public static function getSubscribedEvents ()
  {
    return [
      FOSUserEvents::REGISTRATION_INITIALIZE => ['onInitialize'],
      FOSUserEvents::REGISTRATION_SUCCESS => ['onSuccess'],
    ];
  }

}