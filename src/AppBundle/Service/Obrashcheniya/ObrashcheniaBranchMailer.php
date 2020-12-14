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
    try
    {
      $attachedPdf = \Swift_Attachment::fromPath($modelObrashcheniaBranch->getPdf());
      $attachedPdf->setFilename('Обращение.pdf');
      $message->attach($attachedPdf);
    } catch (\Exception $exception)
    {
      $this->logger->warn('Unable to attached pdf file in send email with appeal: ' . $exception);
    }

    $fileSize = 0;
    foreach ($modelObrashcheniaBranch->getAttachedFiles() as $filesAttach)
    {
      try
      {
        if (file_exists($filesAttach))
        {
          $fileSize += filesize($filesAttach);
        }
      } catch (\Exception $exception)
      {
        $this->logger->warn('Unable get attached file size in send email appeal: ' . $exception);
      }
    }

    if ($fileSize < 20971520) // 20 * 1024 * 1024 = 20МБайт
    {
      foreach ($modelObrashcheniaBranch->getAttachedFiles() as $filesAttach)
      {
        try
        {
          if (file_exists($filesAttach))
          {
            $attachedFile = \Swift_Attachment::fromPath($filesAttach);
            $names = explode('/', $filesAttach);
            $name = end($names);
            $attachedFile->setFilename($name);
            $message->attach($attachedFile);
          }
        } catch (\Exception $exception)
        {
          $this->logger->warn('Unable to attached user file in send email with appeal: ' . $exception);
        }
      }
    }

    try
    {
      $this->mailer->send($message);
    } catch (\Exception $exception)
    {
      $this->logger->warn('Unable to send notification about new appeal to company branch: ' . $exception);
    }
  }
}