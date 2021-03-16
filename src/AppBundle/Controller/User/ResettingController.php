<?php

namespace AppBundle\Controller\User;

use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \FOS\UserBundle\Controller\ResettingController as BaseResettingController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ResettingController
 * @package AppBundle\Controller\User
 */
class ResettingController extends BaseResettingController
{
  /**
   * @var BaseResettingController
   */
  private $baseController;

  private $eventDispatcher;
  private $formFactory;
  private $userManager;
  private $tokenGenerator;
  private $mailer;

  /**
   * @var int
   */
  private $retryTtl;

  public function __construct(
    BaseResettingController $baseController,
    EventDispatcherInterface $eventDispatcher,
    FactoryInterface $formFactory,
    UserManagerInterface $userManager,
    TokenGeneratorInterface $tokenGenerator,
    MailerInterface $mailer,
    $retryTtl
  )
  {
    $this->baseController = $baseController;
    $this->eventDispatcher = $eventDispatcher;
    $this->formFactory = $formFactory;
    $this->userManager = $userManager;
    $this->tokenGenerator = $tokenGenerator;
    $this->mailer = $mailer;
    $this->retryTtl = $retryTtl;

    parent::__construct($eventDispatcher, $formFactory, $userManager, $tokenGenerator, $mailer, $retryTtl);
  }

  /**
   * Отображение страницы с сообщением, что ссылка устарела
   *
   * @return Response
   */
  public function referenceOutdatedAction()
  {
    return $this->render('@FOSUser/Resetting/reference_outdate.html.twig');
  }
}
