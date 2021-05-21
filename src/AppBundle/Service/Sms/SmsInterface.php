<?php

namespace AppBundle\Service\Sms;

use AppBundle\Service\Registration\PhoneVerification\PhoneVerificationRequest;

interface SmsInterface
{
  /**
   * @param PhoneVerificationRequest $phoneVerificationRequest
   * @param $message
   * @return mixed
   */
  public function send (PhoneVerificationRequest $phoneVerificationRequest, $message);

  /**
   * @return string
   */
  public function getName ();
}
