<?php

namespace AppBundle\Service\Organization;

use Accurateweb\ApplicationSettingsAdminBundle\Model\Manager\SettingManagerInterface;
use Accurateweb\TaskSchedulerBundle\Model\BackgroundJob;
use Accurateweb\EmailTemplateBundle\Email\Factory\EmailFactory;
use AppBundle\Model\Obrashchenia\AppealDataToCompany;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;

class OrganizationImportMailer
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
   * @param BackgroundJob $job
   */
  public function send($job)
  {
    $context = $this->router->getContext();
    $baseUrl = $context->getScheme() . '://' . $context->getHost() . $context->getBaseUrl();

    $message = $this->emailFactory->createMessage('organization_import_notification', [
      $this->mailerFrom => $this->mailerSenderName,
    ],
      $this->settingManager->getValue('administrator_email'),
      [
        'social_instagram' => $this->settingManager->getValue('social_instagram'),
        'contact_email' => $this->settingManager->getValue('contact_email'),
        'logo' => $baseUrl . '/local/templates/kdteam/images/png/header/logo-oms.png',
        'illustration' => $baseUrl . '/local/templates/kdteam/images/pages/home/Illustration3.svg',
        'date' => $job->getFinishedAt()?$job->getFinishedAt()->format('d.m.Y H:i'):date('d.m.Y H:i'),
        'result' => $job->getStatusCode()?'завершился ошибкой':'завершился успешно',
      ]
    );

    $this->mailer->send($message);
  }
}
