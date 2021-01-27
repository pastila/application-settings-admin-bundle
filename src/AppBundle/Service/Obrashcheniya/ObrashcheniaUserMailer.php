<?php

namespace AppBundle\Service\Obrashcheniya;

use Accurateweb\ApplicationSettingsAdminBundle\Model\Manager\SettingManagerInterface;
use Accurateweb\EmailTemplateBundle\Email\Factory\EmailFactory;
use AppBundle\Model\Obrashchenia\AppealDataToCompany;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;

class ObrashcheniaUserMailer
{
  private $mailer;
  private $emailFactory;
  private $mailerFrom;
  private $mailerSenderName;
  private $logger;
  private $router;
  private $settingManager;

  public function __construct(
    \Swift_Mailer $mailer,
    EmailFactory $emailFactory,
    $mailerFrom,
    $mailerSenderName,
    LoggerInterface $logger,
    RouterInterface $router,
    SettingManagerInterface $settingManager
  )
  {
    $this->mailer = $mailer;
    $this->emailFactory = $emailFactory;
    $this->mailerFrom = $mailerFrom;
    $this->mailerSenderName = $mailerSenderName;
    $this->logger = $logger;
    $this->router = $router;
    $this->settingManager = $settingManager;
  }

  /**
   * @param AppealDataToCompany $modelObrashcheniaBranch
   */
  public function send(AppealDataToCompany $modelObrashcheniaBranch)
  {
    $context = $this->router->getContext();
    $baseUrl = $context->getScheme() . '://' . $context->getHost() . $context->getBaseUrl();

    $message = $this->emailFactory->createMessage('appeal_sent_user', [
      $this->mailerFrom => $this->mailerSenderName,
    ],
      $modelObrashcheniaBranch->getAuthorEmail(),
      [
        'social_instagram' => $this->settingManager->getValue('social_instagram'),
        'contact_email' => $this->settingManager->getValue('contact_email'),
        'recipient_name' => $modelObrashcheniaBranch->getAuthorFullName(),
        'logo' => $baseUrl . '/local/templates/kdteam/images/png/header/logo-oms.png',
        'illustration' => $baseUrl . '/local/templates/kdteam/images/pages/home/Illustration3.svg',
      ]
    );

    $this->mailer->send($message);
  }
}
