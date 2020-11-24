<?php

namespace AppBundle\Service\Feedback;

use Accurateweb\EmailTemplateBundle\Email\Factory\EmailFactory;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Entity\ContactUs\ContactUs;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class FeedbackMailer
{
  protected $mailer;
  protected $emailFactory;
  protected $mailerFrom;
  protected $mailerSenderName;
  protected $router;

  public function __construct(
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
   * @param Feedback $feedback
   */
  public function sendFeedback(Feedback $feedback)
  {
    $message = $this->emailFactory->createMessage('email_feedback', [
      $this->mailerFrom => $this->mailerSenderName,
    ],
      'todo@todo.ru',
      [
        'date' => $feedback->getCreatedAt()->format('Y-m-d H:i:s'),
        'url' => $this->router->generate('app_insurancecompany_feedback_show', [
          'id' => $feedback->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
      ]
    );
    $this->mailer->send($message);
  }
}