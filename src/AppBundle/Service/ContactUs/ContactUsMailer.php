<?php

namespace AppBundle\Service\ContactUs;

use Accurateweb\ApplicationSettingsAdminBundle\Model\Manager\SettingManagerInterface;
use Accurateweb\EmailTemplateBundle\Email\Factory\EmailFactory;
use AppBundle\Entity\ContactUs\ContactUs;
use Symfony\Component\Routing\RouterInterface;

class ContactUsMailer
{
  protected $mailer;
  protected $emailFactory;
  protected $mailerFrom;
  protected $mailerSenderName;
  protected $router;
  protected $settingManager;

  public function __construct (
    \Swift_Mailer $mailer,
    EmailFactory $emailFactory,
    $mailerFrom,
    $mailerSenderName,
    SettingManagerInterface $settingManager,
    RouterInterface $router
  )
  {
    $this->mailer = $mailer;
    $this->emailFactory = $emailFactory;
    $this->mailerFrom = $mailerFrom;
    $this->mailerSenderName = $mailerSenderName;
    $this->router = $router;
    $this->settingManager = $settingManager;
  }

  /**
   * @param ContactUs $contactUs
   */
  public function sendContactUs (ContactUs $contactUs)
  {
    $mail = $this->settingManager->getValue('default_email');

    $message = $this->emailFactory->createMessage('email_contact_us', [
      $this->mailerFrom => $this->mailerSenderName,
    ],
      $mail,
      [
        'name' => $contactUs->getAuthorName(),
        'email' => $contactUs->getEmail(),
        'text' => $contactUs->getMessage(),
      ]
    );
    $this->mailer->send($message);
  }
}