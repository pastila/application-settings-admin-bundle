<?php


namespace AppBundle\Service\UserMailer;

use Accurateweb\ApplicationSettingsAdminBundle\Model\Manager\SettingManagerInterface;
use Accurateweb\EmailTemplateBundle\Email\Factory\EmailFactory;
use FOS\UserBundle\Mailer\Mailer as BaseMailer;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class Mailer
 * @package AppBundle\Service\UserMailer
 */
class Mailer implements MailerInterface
{
  private $baseMailer;
  private $settingManager;
  private $emailFactory;
  private $mailerFrom;
  private $mailerSenderName;
  protected $mailer;
  protected $router;
  protected $templating;
  protected $parameters;

  /**
   * Mailer constructor.
   * @param BaseMailer $baseMailer
   * @param EmailFactory $emailFactory
   * @param $mailerFrom
   * @param $mailerSenderName
   * @param SettingManagerInterface $settingManager
   * @param $mailer
   * @param UrlGeneratorInterface $router
   * @param EngineInterface $templating
   * @param array $parameters
   */
  public function __construct(
    BaseMailer $baseMailer,
    EmailFactory $emailFactory,
    $mailerFrom,
    $mailerSenderName,
    SettingManagerInterface $settingManager,
    $mailer,
    UrlGeneratorInterface $router,
    EngineInterface $templating,
    array $parameters)
  {
    $this->baseMailer = $baseMailer;
    $this->settingManager = $settingManager;
    $this->emailFactory = $emailFactory;
    $this->mailerFrom = $mailerFrom;
    $this->mailerSenderName = $mailerSenderName;
    $this->mailer = $mailer;
    $this->router = $router;
    $this->templating = $templating;
    $this->parameters = $parameters;
  }

  /**
   * @param UserInterface $user
   */
  public function sendResettingEmailMessage(UserInterface $user)
  {
    $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

    $message = $this->emailFactory->createMessage('resseting_email', [
      $this->mailerFrom => $this->mailerSenderName,
    ],
      $user->getEmail(),
      [
        'social_instagram' => $this->settingManager->getValue('social_instagram'),
        'contact_email' => $this->settingManager->getValue('contact_email'),
        'url' => $url,
        'recipient_name' => $user->getUsername(),
      ]
    );
    $this->mailer->send($message);
  }

  /**
   * {@inheritdoc}
   */
  public function sendConfirmationEmailMessage(UserInterface $user)
  {
    $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

    $message = $this->emailFactory->createMessage('confirmation_email', [
      $this->mailerFrom => $this->mailerSenderName,
    ],
      $user->getEmail(),
      [
        'url' => $url,
        'recipient_name' => $user->getUsername(),
        'social_instagram' => $this->settingManager->getValue('social_instagram'),
        'contact_email' => $this->settingManager->getValue('contact_email'),
      ]
    );
    $this->mailer->send($message);
  }
}