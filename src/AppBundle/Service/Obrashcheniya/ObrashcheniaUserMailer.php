<?php

namespace AppBundle\Service\Obrashcheniya;

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

  public function __construct(
    \Swift_Mailer $mailer,
    EmailFactory $emailFactory,
    $mailerFrom,
    $mailerSenderName,
    LoggerInterface $logger,
    RouterInterface $router
  )
  {
    $this->mailer = $mailer;
    $this->emailFactory = $emailFactory;
    $this->mailerFrom = $mailerFrom;
    $this->mailerSenderName = $mailerSenderName;
    $this->logger = $logger;
    $this->router = $router;
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
      $modelObrashcheniaBranch->getEmailsTo(),
      [
        'author' => $modelObrashcheniaBranch->getAuthor(),
        'logo' => $baseUrl . '/local/templates/kdteam/images/png/header/logo-oms.png',
        'illustration' => $baseUrl . '/local/templates/kdteam/images/pages/home/Illustration3.svg',
      ]
    );

    $this->mailer->send($message);
  }
}
