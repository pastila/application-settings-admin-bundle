<?php


namespace AppBundle\EventListener;

use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\EventListener\ResettingListener as BaseResettingListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
   * ResettingListener constructor.
   *
   * @param UrlGeneratorInterface $router
   * @param int                   $tokenTtl
   */
  public function __construct(UrlGeneratorInterface $router, $tokenTtl)
  {
    $this->router = $router;
    $this->tokenTtl = $tokenTtl;

    parent::__construct($router, $tokenTtl);
  }

  /**
   * @param GetResponseUserEvent $event
   */
  public function onResettingResetInitialize(GetResponseUserEvent $event)
  {
    if (!$event->getUser()->isPasswordRequestNonExpired($this->tokenTtl)) {
      $event->setResponse(new RedirectResponse($this->router->generate('fos_user_resetting_reference_outdated')));
    }
  }
}