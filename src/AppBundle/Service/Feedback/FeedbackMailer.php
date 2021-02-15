<?php

namespace AppBundle\Service\Feedback;

use Accurateweb\ApplicationSettingsAdminBundle\Model\Manager\SettingManagerInterface;
use Accurateweb\EmailTemplateBundle\Email\Factory\EmailFactory;
use AppBundle\Entity\Company\Feedback;
use AppBundle\Twig\DateExtension;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class FeedbackMailer
{
  protected $mailer;
  protected $emailFactory;
  protected $mailerFrom;
  protected $mailerSenderName;
  protected $router;
  protected $settingManager;

  public function __construct(
    \Swift_Mailer $mailer,
    EmailFactory $emailFactory,
    $mailerFrom,
    $mailerSenderName,
    SettingManagerInterface $settingManager,
    RouterInterface $router
  )
  {
    $this->mailer = $mailer;
    $this->emailFactory = $emailFactory;
    $this->mailerFrom = $mailerFrom;
    $this->mailerSenderName = $mailerSenderName;
    $this->router = $router;
    $this->settingManager = $settingManager;
  }

  /**
   * @param Feedback $feedback
   * @param $email
   */
  public function sendFeedback(Feedback $feedback, $email)
  {
    $ext = new DateExtension();
    $date = $ext->prepareDate($feedback->getCreatedAt(), 'd F, Y');

    $message = $this->emailFactory->createMessage('email_feedback', [
      $this->mailerFrom => $this->mailerSenderName,
    ],
      $email,
      [
        'social_instagram' => $this->settingManager->getValue('social_instagram'),
        'contact_email' => $this->settingManager->getValue('contact_email'),
        'date' => $date,
        'url' => $this->router->generate('app_insurancecompany_feedback_show', [
          'id' => $feedback->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
      ]
    );
    if (!empty($this->settingManager->getValue('administrator_email')) &&
      $email !== $this->settingManager->getValue('administrator_email'))
    {
      $message->setBcc([$this->settingManager->getValue('administrator_email')]);
    }
    $this->mailer->send($message);
  }
}