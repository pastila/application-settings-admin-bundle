<?php


namespace AppBundle\EventListener;

use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\EventListener\ResettingListener as BaseResettingListener;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\UserBundle\Event\FormEvent;

/**
 * Class ResettingListener
 * @package AppBundle\EventListener
 */
class ResettingListener extends BaseResettingListener
{
  /**
   * @var UrlGeneratorInterface
   */
  private $router;

  /**
   * @var int
   */
  private $tokenTtl;

  /**
   * @var SessionInterface
   */
  private $session;

  /**
   * ResettingListener constructor.
   * @param UrlGeneratorInterface $router
   * @param $tokenTtl
   * @param SessionInterface $session
   */
  public function __construct(UrlGeneratorInterface $router, $tokenTtl, SessionInterface $session)
  {
    $this->router = $router;
    $this->tokenTtl = $tokenTtl;
    $this->session = $session;

    parent::__construct($router, $tokenTtl);
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents()
  {
    return array_merge(parent::getSubscribedEvents(), [
      FOSUserEvents::RESETTING_SEND_EMAIL_INITIALIZE => 'onResettingSendEmailInitialize',
      FOSUserEvents::RESETTING_RESET_SUCCESS => 'onPasswordResettingSuccess',
    ]);
  }

  /**
   * @param GetResponseUserEvent $event
   */
  public function onResettingResetInitialize(GetResponseUserEvent $event)
  {
    if (!$event->getUser()->isPasswordRequestNonExpired($this->tokenTtl))
    {
      $this->session->getFlashBag()->add('errors', 'Ссылка устарела и больше не является действительной!');
      $event->setResponse(new RedirectResponse($this->router->generate('fos_user_resetting_request')));
    }
  }

  /**
   * @param GetResponseUserEvent $event
   */
  public function onResettingSendEmailInitialize(GetResponseUserEvent $event)
  {
    if (!$event->getUser())
    {
      $this->session->getFlashBag()->add('errors', 'Учетная запись с таким e-mail не найдена!');
      $event->setResponse(new RedirectResponse($this->router->generate('fos_user_resetting_request')));
    }
  }

  /**
   * @param FormEvent $event
   */
  public function onPasswordResettingSuccess(FormEvent $event)
  {
    $url = $this->router->generate('lk_index');

    $event->setResponse(new RedirectResponse($url));
  }
}