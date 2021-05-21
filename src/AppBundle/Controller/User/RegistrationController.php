<?php

namespace AppBundle\Controller\User;

use Accurateweb\ApplicationSettingsAdminBundle\Model\Manager\SettingManagerInterface;
use AppBundle\Entity\User\User;
use AppBundle\Service\Registration\PhoneVerification\PhoneVerificationRequestManager;
use AppBundle\Validator\User\Phone;
use FOS\UserBundle\Controller\RegistrationController as BaseRegistrationController;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * Class RegistrationController
 * @package AppBundle\Controller\User
 */
class RegistrationController extends BaseRegistrationController
{
  private $baseController;
  private $eventDispatcher;
  private $formFactory;
  private $userManager;
  private $tokenStorage;
  private $settingManager;
  private $session;
  private $phoneVerificationRequestManager;

  /**
   * RegistrationController constructor.
   * @param BaseRegistrationController $baseController
   * @param EventDispatcherInterface $eventDispatcher
   * @param FactoryInterface $formFactory
   * @param UserManagerInterface $userManager
   * @param TokenStorageInterface $tokenStorage
   * @param SettingManagerInterface $settingManager
   * @param SessionInterface $session
   * @param PhoneVerificationRequestManager $phoneVerificationRequestManager
   */
  public function __construct(
    BaseRegistrationController $baseController,
    EventDispatcherInterface $eventDispatcher,
    FactoryInterface $formFactory,
    UserManagerInterface $userManager,
    TokenStorageInterface $tokenStorage,
    SettingManagerInterface $settingManager,
    SessionInterface $session,
    PhoneVerificationRequestManager $phoneVerificationRequestManager
  )
  {
    $this->baseController = $baseController;
    $this->eventDispatcher = $eventDispatcher;
    $this->formFactory = $formFactory;
    $this->userManager = $userManager;
    $this->tokenStorage = $tokenStorage;
    $this->settingManager = $settingManager;
    $this->session = $session;
    $this->phoneVerificationRequestManager = $phoneVerificationRequestManager;

    parent::__construct($eventDispatcher, $formFactory, $userManager, $tokenStorage);
  }

  /**
   * @Route(path="/register/generate-sms-code", name="register_generate_sms_code")
   * @param Request $request
   * @return JsonResponse
   * @throws \Exception
   */
  public function generateSmsCodeAction(Request $request)
  {
    $phone = $request->query->get('phone');

    if (!$phone)
    {
      return new JsonResponse([
        'message' => 'Номер телефона не может быть пустым!'
      ], 422);
    }

    $violations = $this->get('validator')->validate($phone, [new Phone()]);

    if (count($violations))
    {
      $errors = [];
      foreach ($violations as $violation)
      {
        /**
         * @var ConstraintViolation $violation
         */
        $errors[] = $violation->getMessage();
      }
      return new JsonResponse([
        'message' => 'Номер телефона содержит ошибки',
        'errors' => $errors
      ], 422);
    }

    $user = $this->userManager->findUserBy([
      'phone' => $phone
    ]);

    if ($user)
    {
      return new JsonResponse([
        'message' => 'Пользователь с таким номером телефона уже существует!'
      ], 406);
    }

    $verificationRequest = $this->phoneVerificationRequestManager->createVerificationRequest($phone);

    if ($this->phoneVerificationRequestManager->sendVerificationCode($verificationRequest))
    {
      return new JsonResponse([
        'status' => 'success'
      ]);
    }

    return new JsonResponse([
      'status' => 'error'
    ]);
  }
}