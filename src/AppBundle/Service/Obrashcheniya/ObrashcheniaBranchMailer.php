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
    if (file_exists($modelObrashcheniaBranch->getPdf()))
    {
      $attachedPdf = \Swift_Attachment::fromPath($modelObrashcheniaBranch->getPdf());
      $attachedPdf->setFilename('obrashcheniya.pdf');
      $message->attach($attachedPdf);
    }
    else
    {
      throw new \InvalidArgumentException(sprintf('Not found pdf appeal %s in sending email appeal', $modelObrashcheniaBranch->getPdf()));
    }

    foreach ($modelObrashcheniaBranch->getAttachedFiles() as $filesAttach)
    {
      if (file_exists($filesAttach))
      {
        $attachedFile = \Swift_Attachment::fromPath($filesAttach);
        $names = explode('/', $filesAttach);
        $name = end($names);
        $attachedFile->setFilename($name);
        $message->attach($attachedFile);
      }
      else
      {
        throw new \InvalidArgumentException(sprintf('Not found attached file %s in sending email appeal', $filesAttach));
      }
    }

    $this->mailer->send($message);
  }
}
