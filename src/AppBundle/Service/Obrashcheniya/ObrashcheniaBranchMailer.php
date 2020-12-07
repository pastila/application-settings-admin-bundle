<?php

namespace AppBundle\Service\Obrashcheniya;

use Accurateweb\EmailTemplateBundle\Email\Factory\EmailFactory;
use AppBundle\Model\Obrashchenia\AppealDataToCompany;
use Symfony\Component\Routing\RouterInterface;

class ObrashcheniaBranchMailer
{
  protected $mailer;
  protected $emailFactory;
  protected $mailerFrom;
  protected $mailerSenderName;

  public function __construct(
    \Swift_Mailer $mailer,
    EmailFactory $emailFactory,
    $mailerFrom,
    $mailerSenderName
  )
  {
    $this->mailer = $mailer;
    $this->emailFactory = $emailFactory;
    $this->mailerFrom = $mailerFrom;
    $this->mailerSenderName = $mailerSenderName;
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
      []
    );
    $attch = \Swift_Attachment::fromPath($modelObrashcheniaBranch->getPdf());
    $attch->setFilename('Обращение.pdf');
    $message->attach($attch);
    foreach ($modelObrashcheniaBranch->getFilesAttach() as $filesAttach)
    {
      $attch = \Swift_Attachment::fromPath($filesAttach);
      $names = explode('/', $filesAttach);
      $name = end($names);
      $attch->setFilename($name);
      $message->attach($attch);
    }

    $this->mailer->send($message);
  }
}