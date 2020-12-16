<?php

namespace AppBundle\Service\Obrashcheniya;

use Accurateweb\EmailTemplateBundle\Email\Factory\EmailFactory;
use AppBundle\Model\Obrashchenia\AppealDataToCompany;
use Psr\Log\LoggerInterface;

class ObrashcheniaBranchMailer
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
    $message = $this->emailFactory->createMessage('email_obrashcheniya_branch', [
      $this->mailerFrom => $this->mailerSenderName,
    ],
      $modelObrashcheniaBranch->getEmailsTo(),
      [
        'author' => $modelObrashcheniaBranch->getAuthor()
      ]
    );
    $attachedPdf = \Swift_Attachment::fromPath($modelObrashcheniaBranch->getPdf());
    $attachedPdf->setFilename('Обращение.pdf');
    $message->attach($attachedPdf);

    foreach ($modelObrashcheniaBranch->getAttachedFiles() as $filesAttach)
    {
      $attachedFile = \Swift_Attachment::fromPath($filesAttach);
      $names = explode('/', $filesAttach);
      $name = end($names);
      $attachedFile->setFilename($name);
      $message->attach($attachedFile);
    }

    $this->mailer->send($message);
  }
}
