<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

namespace AppBundle\Validator\Constraints;

interface PhoneVerificationAwareInterface
{
  public function getVerifiedPhone();

  public function getVerificationCode();
}
