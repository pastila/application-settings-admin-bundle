<?php

namespace AppBundle\Service\Obrashcheniya;

use Accurateweb\EmailTemplateBundle\Email\Factory\EmailFactory;
use AppBundle\Model\Obrashchenia\AppealDataToCompany;
use Psr\Log\LoggerInterface;

class ObrashcheniaUserMailer
{
  protected $mailer;
  protected $emailFactory;
  protected $mailerFrom;
  protected $mailerSenderName;
  protected $logger;

  public function __construct(
    \Swift_Mailer $mailer,
    EmailFactory $emailFactory,
    $mailerFrom,
    $mailerSenderName,
    LoggerInterface $logger
  )
  {
    $this->mailer = $mailer;
    $this->emailFactory = $emailFactory;
    $this->mailerFrom = $mailerFrom;
    $this->mailerSenderName = $mailerSenderName;
    $this->logger = $logger;
  }

  /**
   * @param AppealDataToCompany $modelObrashcheniaBranch
   */
  public function send(AppealDataToCompany $modelObrashcheniaBranch)
  {
    $message = $this->emailFactory->createMessage('appeal_sent_user', [
      $this->mailerFrom => $this->mailerSenderName,
    ],
      $modelObrashcheniaBranch->getEmailsTo(),
      [
        'author' => $modelObrashcheniaBranch->getAuthor()
      ]
    );

    $this->mailer->send($message);
  }
}
