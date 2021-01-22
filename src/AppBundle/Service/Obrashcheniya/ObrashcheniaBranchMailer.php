<?php

namespace AppBundle\Service\Obrashcheniya;

use Accurateweb\ApplicationSettingsAdminBundle\Model\Manager\SettingManagerInterface;
use Accurateweb\EmailTemplateBundle\Email\Factory\EmailFactory;
use AppBundle\Model\Obrashchenia\AppealDataToCompany;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class ObrashcheniaBranchMailer
{
  protected $mailer;
  protected $emailFactory;
  protected $mailerFrom;
  protected $mailerSenderName;
  protected $logger;
  protected $settingManager;

  public function __construct(
    \Swift_Mailer $mailer,
    EmailFactory $emailFactory,
    $mailerFrom,
    $mailerSenderName,
    SettingManagerInterface $settingManager,
    LoggerInterface $logger
  )
  {
    $this->mailer = $mailer;
    $this->emailFactory = $emailFactory;
    $this->mailerFrom = $mailerFrom;
    $this->mailerSenderName = $mailerSenderName;
    $this->logger = $logger;
    $this->settingManager = $settingManager;
  }

  /**
   * @param AppealDataToCompany $modelObrashcheniaBranch
   * @throws \Swift_TransportException
   */
  public function send(AppealDataToCompany $modelObrashcheniaBranch)
  {
    $message = $this->emailFactory->createMessage('email_obrashcheniya_branch', [
      $this->mailerFrom => $this->mailerSenderName,
    ],
      $modelObrashcheniaBranch->getEmailsTo(),
      [
        'author' => $modelObrashcheniaBranch->getAuthorFullName()
      ]
    );
    if (!empty($this->settingManager->getValue('administrator_email')))
    {
      $message->setBcc([$this->settingManager->getValue('administrator_email')]);
    }

    if (file_exists($modelObrashcheniaBranch->getPdf()))
    {
      $attachedPdf = \Swift_Attachment::fromPath($modelObrashcheniaBranch->getPdf());
      $attachedPdf->setFilename('obrashcheniya.pdf');
      $message->attach($attachedPdf);
    }
    else
    {
      throw new FileNotFoundException(sprintf('Not found pdf appeal %s in sending email appeal', $modelObrashcheniaBranch->getPdf()));
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
        throw new FileNotFoundException(sprintf('Not found attached file %s in sending email appeal', $filesAttach));
      }
    }

    try
    {
      $this->mailer->send($message);
    } catch (\Swift_TransportException $e)
    {
      // fix Timeout waiting for data from client
      if ($e->getCode() === 421)
      {
        // Повторная попытка отправки
        $this->mailer->getTransport()->stop();
        $this->mailer->getTransport()->start();
        $this->mailer->send($message);
      }
      else {
        throw $e;
      }
    }
  }
}
