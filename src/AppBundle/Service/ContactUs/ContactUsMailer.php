<?php

namespace AppBundle\Service\ContactUs;

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

  public function __construct (
    \Swift_Mailer $mailer,
    EmailFactory $emailFactory,
    $mailerFrom,
    $mailerSenderName,
    RouterInterface $router
  )
  {
    $this->mailer = $mailer;
    $this->emailFactory = $emailFactory;
    $this->mailerFrom = $mailerFrom;
    $this->mailerSenderName = $mailerSenderName;
    $this->router = $router;
  }

  /**
   * @param ContactUs $contactUs
   */
  public function sendContactUs (ContactUs $contactUs)
  {
    $message = $this->emailFactory->createMessage('email_contact_us', [
      $this->mailerFrom => $this->mailerSenderName,
    ],
      'todo@todo.ru',
      [
        'name' => $contactUs->getAuthorName(),
        'email' => $contactUs->getEmail(),
        'text' => $contactUs->getMessage(),
      ]
    );
    $this->mailer->send($message);
  }
}