<?php

namespace AppBundle\Controller\User;

use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\ProfileController as BaseProfileController;

/**
 * Class ProfileController
 * @package AppBundle\Controller\User
 */
class ProfileController extends BaseProfileController
{
  /**
   * @var BaseProfileController
   */
  private $baseController;

  public function __construct(
    BaseProfileController $baseController,
    EventDispatcherInterface $eventDispatcher,
    FactoryInterface $formFactory,
    UserManagerInterface $userManager)
  {
    $this->baseController = $baseController;
    parent::__construct($eventDispatcher, $formFactory, $userManager);
  }

  /**
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
   */
  public function showAction()
  {
    $this->denyAccessUnlessGranted(['ROLE_USER']);

    // TODO: заглушка, до момента реализации персонального кабинета в Symfony
    return $this->redirect('/personal-cabinet/', 301);
  }
}
