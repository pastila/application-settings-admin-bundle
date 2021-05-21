<?php

namespace AppBundle\EventListener\Security;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RegistrationCompleteUrlListener implements EventSubscriberInterface
{
  /*
   * Запоминаем backUrl на странице регистрации
   */
  public function onInitialize (GetResponseUserEvent $event)
  {
    $request = $event->getRequest();

    if ($request->get('backUrl'))
    {
      $request->getSession()->set('registration_complete_url', $request->get('backUrl'));
    }
  }

  /*
   * После подтверждения email редиректим на нужную страницу
   */
  public function onSuccess (FilterUserResponseEvent $event)
  {
    $request = $event->getRequest();

    if ($request->getSession()->get('registration_complete_url'))
    {
      $event->getResponse()->setTargetUrl($request->getSession()->get('registration_complete_url'));
      $request->getSession()->remove('registration_complete_url');
    }
  }

  public static function getSubscribedEvents ()
  {
    return [
      FOSUserEvents::REGISTRATION_INITIALIZE => ['onInitialize'],
      FOSUserEvents::REGISTRATION_CONFIRMED => ['onSuccess'],
    ];
  }

}