<?php

namespace AppBundle\Service\Sms;

use AppBundle\Service\Registration\PhoneVerification\PhoneVerificationRequest;
use Psr\Log\LoggerInterface;

class FileSmsHandler implements SmsInterface
{
  private $smsFileLogger;

  public function __construct (LoggerInterface $smsFileLogger)
  {
    $this->smsFileLogger = $smsFileLogger;
  }

  /**
   * @param PhoneVerificationRequest $phoneVerificationRequest
   * @param string $message
   */
  public function send (PhoneVerificationRequest $phoneVerificationRequest, $message)
  {
    $this->smsFileLogger->info(sprintf("New sms. Phone: %s\nMessage: %s", $phoneVerificationRequest->getPhone(), $message));
  }

  public function getName ()
  {
    return 'file';
  }
}